<?php

namespace App\Console\Commands;

use App\Events\TeamInvited;
use Illuminate\Console\Command;
use Enqueue\RdKafka\RdKafkaConnectionFactory;

class ConsumeTeamInvitedEvents extends Command
{
    protected $signature = 'kafka:consume {topic}';
    protected $description = 'Consume TeamInvited events from Kafka';

    public function handle()
    {
        $topic = $this->argument('topic');

        $connectionFactory = new RdKafkaConnectionFactory([
            'global' => [
                'group.id' => 'team_invited_group',
                'metadata.broker.list' => env('KAFKA_BROKERS', 'kafka:9092'),
                'enable.auto.commit' => 'false',
            ],
            'topic' => [
                'auto.offset.reset' => 'earliest',
            ],
        ]);

        $context = $connectionFactory->createContext();
        $queue = $context->createQueue($topic);
        $consumer = $context->createConsumer($queue);

        $this->info("Listening for TeamInvited events on topic: {$topic}");

        while (true) {
            $message = $consumer->receive();

            if ($message) {
                try {
                    $body = json_decode($message->getBody(), true);

                    // Проверяем, что это нужное событие
                    if (isset($body['type']) && $body['type'] === 'TeamInvited' && isset($body['data'])) {
                        $invitationData = $body['data'];

                        // Вызываем Laravel событие с массивом
                        event(new TeamInvited($invitationData));

                        $this->info("TeamInvited event dispatched for invitation ID: " . ($invitationData['id'] ?? 'unknown'));
                    }

                    $consumer->acknowledge($message);
                } catch (\Exception $e) {
                    $this->error("Error processing message: " . $e->getMessage());
                    $consumer->reject($message);
                }
            }
        }
    }
}
