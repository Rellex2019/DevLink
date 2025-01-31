<template>
    <textarea @input="updateContent" class="textarea" v-model="localContent" > </textarea>
</template>
<script setup>
import { ref, watch } from 'vue';


const props = defineProps({
    content: String,
    id:Number
});
const emit = defineEmits();
const localContent = ref(props.content);

watch(() => props.content, (newContent) => {
    localContent.value = newContent;
});
function updateContent()
{
    emit('update-content', localContent.value, props.id)
}
</script>
<style>
.textarea{
    vertical-align: top;
    text-align: start;
    background-color: #161718;
    border: none;
    outline: none;
    height: 100%;
    width: 100%;
    resize: none;
}

</style>