<template>
    <div class="profile">
        <div class="person-block">
            <div class="img-container">
                <img v-if="teamInfo.team && teamInfo.team.logo" :src="teamInfo.team.logo" alt="avatar"
                    class="img-avatar">
                <img v-else src="@/svg/default-avatar.svg" alt="avatar" class="img-avatar">
            </div>
            <div class="person-name" v-if="teamInfo.team && teamInfo.team.name">{{ teamInfo.team.name }}</div>
            <!-- <div class="person-description" v-if="teamInfo.bio" v-html="teamInfo.bio"></div> -->
            <div class="contacts">
                <div class="location" v-if="teamInfo.team && teamInfo.team.email">
                    <div class="svg"><img src="@/svg/email.svg" alt=""></div>
                    <div class="location-name">{{ teamInfo.team.email }}</div>
                    {{ teamInfo.team.members }}
                </div>
            </div>
        </div>







        <div class="member-block">
            <div class="search-container">
                <div class="search">
                    <div style="width: 50%; max-width: 500px;">
                        <Search :placeholderText="'Найти участника'" style="flex: 1;"
                            @write-input="handleSearchInput" />
                    </div>
                    <div class="container-count-member" v-if="teamInfo.members">
                        {{ teamInfo.members.length }} {{ pluralize(teamInfo.members.length, ['участник', 'участника',
                            'участников']) }}
                    </div>
                </div>

                <button class="leave-team-btn">Покинуть команду</button>
            </div>

            <div v-if="teamInfo.members && teamInfo.members.length" class="container-member">
                <div v-for="member in filteredMembers" :key="member.id">
                    <div class="member">
                        <div class="container-member-avatar" @click="goToUser(member)">

                            <img :src="member.profile.avatar" alt="" class="member-avatar">
                        </div>
                        <div class="member-info">
                            <div class="member-name" @click="goToUser(member)">{{ member.name }}
                            </div>
                            <div class="member-access">{{ member.role }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="empty-message">
                Нет доступных пользователей
            </div>

        </div>
    </div>
</template>
<script>
import Search from '../input/Search.vue';

export default {
    name: 'TeamMembers',
    data() {
        return {
            searchQuery: ''
        }
    },
    props: {
        teamInfo: {
            required: true,
        }
    },
    methods: {
        handleSearchInput(text) {
            this.searchQuery = text;
        },
        goToUser(user) {
            if (!user.name) {
                console.error('Ника пользователя не существует');
                return;
            }

            this.$router.push(`/${user.name}`);
        },
        pluralize(count, words) {
            const cases = [2, 0, 1, 1, 1, 2];
            return words[
                count % 100 > 4 && count % 100 < 20 ? 2 : cases[Math.min(count % 10, 5)]
            ];
        }
    },
    computed: {
        filteredMembers() {
            if (!this.teamInfo.members || this.teamInfo.members.length === 0) return [];

            if (this.searchQuery.trim() === '') {
                return this.teamInfo.members;
            }

            return this.teamInfo.members.filter(member =>
                member.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    components: {
        Search
    }
}
</script>
<style scoped>
.profile {
    display: flex;
    justify-content: space-between;
    gap: clamp(5px, 2.5vw, 40px);
    margin-top: 80px;
    width: 84vw;
    max-width: 1600px;
}

.person-block {
    display: flex;
    flex-direction: column;
    gap: 25px;
    width: 25%;
    max-width: 500px;
    color: #FFFFFF;
}



.img-avatar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.img-container {
    position: relative;
    width: 50%;
    padding-top: 50%;
    border-radius: 50%;
    height: 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0px 0px 6px #4B4F53;
    background-color: #101112;
}

.person-name {
    font-size: 24px;
    font-weight: bold;
}

.contacts {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-size: 15px;
}





.person-name::after {
    margin-top: 10px;
    content: '';
    display: block;
    width: 100%;
    height: 1px;
    background-color: #343A40;
}

.location {
    display: flex;
    align-items: center;
    gap: 10px;
}

.svg,
.social-icon {
    margin-top: 2px;
}

.svg img {
    width: 20px;
    height: 20px;
}

.socials {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.social {
    display: flex;
    gap: 10px;
}

.social a {
    text-decoration: none;
    color: inherit;
}

.social a:hover {
    text-decoration: underline;
}

.name-team-block {
    font-size: 20px;
    font-weight: bold;
}

.container-team-img {

    margin-top: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.container-team-img a {
    overflow: hidden;
    border-radius: 5vw;
    width: clamp(20px, 3vw, 50px);
    height: clamp(20px, 3vw, 50px);

}

.container-team-img a img {
    width: 100%;
    height: 100%;
}

.person-block textarea {
    background-color: #161718;
    border: 1px solid #343A40;
    color: #ddd;
    padding: 8px 10px;
    box-sizing: border-box;
    width: 100%;
    min-height: 100px;
    font-size: 16px;
    resize: none;
    border-radius: 9px;
}

.location-name input,
.container-social-info input {
    width: 100%;
    background-color: #161718;
    border: 1px solid #343A40;
    color: #ddd;
    padding: 8px 10px;
    border-radius: 9px;
}

.container-btns {
    gap: 10px;
    display: flex;
}

.container-btns button {
    cursor: pointer;
    padding: 6px 10px;
    color: #FFF;
    border-radius: 7px;
}

.save-btn {
    border: 1px solid #EDB200;
    background-color: #edb20033;
}

.save-btn:hover {
    background-color: #edb2004b;
}

.cancel-btn {
    border: 1px solid #4B4F53;
    background: none;
}

.cancel-btn:hover {
    background-color: #4b4f5327;
}

.plus-img {
    top: calc(50% - 35px);
    left: calc(50% - 17px);
    font-size: 60px;
    color: #bebebe;
    position: absolute;
}

.avatar-change {
    cursor: pointer;
    filter: brightness(0.4);
}

.socials-edit {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.social-edit {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.container-social-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}



.empty-message {
    padding: 20px;
    text-align: center;
    color: #666;
}






/* РЕПОЗИТОРИИ */
.member-block {
    flex: 1;
}

.container-member {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.member {
    border-radius: 5px;
    border: 1px solid #343A40;
    padding: 15px 20px;
    color: #F8F9FA;
    display: flex;
    gap: 15px;
    align-items: center;
}

.member-info {

    display: flex;
    gap: 15px;
}

.member-name {
    cursor: pointer;
    color: #EDB200;
    font-size: 20px;
}

.member-name:hover {
    text-decoration: underline;
}

.member-access {
    font-family: 'Fira Code';

    padding: 4px 13px;
    font-size: 13px;
    background: none;
    border: 1px solid #343A40;
    border-radius: 15px;
}

.last-update {
    font-size: 15px;
    color: #F8F9FA60;
}

.container-member-avatar {
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 25px;
    overflow: hidden;
    border: 1px solid #343A4050;
}

.member-avatar {
    width: 100%;
    height: 100%;
}

.search-container {
    display: flex;
    justify-content: space-between;
}

.search {
    display: flex;
    gap: 10px;
    align-items: center;
    width: 100%;

}

.leave-team-btn {
    cursor: pointer;
    background: none;
    border: 1px solid #FF0E0E;
    padding: 5px 10px;
    border-radius: 5px;
    color: #FF0E0E;
    flex: none;
}

.leave-team-btn:hover {
    color: #F8F9FA;
    background-color: #FF0E0E;
}

.container-count-member {
    color: #F8F9FA;
    font-size: 16px;
    border: 1px solid #343A40;
    border-radius: 15px;
    padding: 5px 10px;
}
</style>