<template>
    <div class="settings-container">


        <div class="settings">
            <div class="settings-block">

                <div class="container-team-image">
                    <div class="team-image">
                        <img v-if="avatarPreview" :src="avatarPreview" alt="avatar" class="img-logo">
                        <img v-else-if="teamInfo.team && teamInfo.team.logo" :src="teamInfo.team.logo" alt="avatar"
                            class="img-logo">
                        <img v-else src="@/svg/default-avatar.svg" alt="avatar" class="img-logo">
                    </div>
                    <input ref="logoInput" type="file" @change="handleFileUpload" accept="image/png, image/jpeg"
                        style="display: none;">
                    <button @click="uploadLogo" class="btn-update-image">Выбрать фото</button>
                </div>

            </div>
            <div class="cointainer-general-info">
                <div class="team-name">
                    <p class="label-input">Название команды</p>
                    <input class="input-settings" v-model="team.name" type="text">
                </div>
                <div class="team-name">
                    <p class="label-input">Публичный Email</p>
                    <input class="input-settings" v-model="team.email" type="email">
                </div>



            </div>
        </div>
        <button class="save-team-btn" @click="updateTeamInfo">Обновить информацию</button>


        <div class="team-delete-container">
            <div>
                <p class="title-delete">Удалить команду</p>
                <p class="ps">После удаления команда исчезнет навсегда, а так же информация о ней из
                    репозиториев.
                    Пожалуйста, будьте уверены.</p>
            </div>
            <button class="delete-team-btn" @click="deleteTeam">Удалить команду</button>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: 'TeamSettings',
    data() {
        return {
            team: { ...this.teamInfo.team },
            form: {
                name: null,
                email: null,
                logoFile: null,
            },
            avatarPreview: null
        }
    },
    props: {
        teamInfo: {
            required: true,
        }
    },
    watch: {
        teamInfo(newInfo) {
            this.team = newInfo.team;
        }
    },
    methods: {
        updateTeamInfo() {
            this.form = {
                ...this.form,
                ...this.team
            };
            const formData = new FormData();

            formData.append('name', this.form.name || '');

            formData.append('email', this.form.email || '');

            if (this.form.logoFile) {
                formData.append('logo', this.form.logoFile);
            }
            axios.post(`/team/${this.team.id}/change`, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
                .then(response => {
                    this.$emit('team-update', response.data);
                    this.$router.push({
                        name: 'team',
                        params: {
                            'team': response.data.name
                        },
                        query:
                        {
                            chapter: 'peoples'
                        }
                    });

                })

        },
        uploadLogo() {
            this.$refs.logoInput.click();
        },
        handleFileUpload() {
            const file = event.target.files[0]
            if (!file) return

            if (!file.type.match('image/png') && !file.type.match('image/jpeg')) {
                this.$showAlert('Пожалуйста, выберите изображение', 'error')
                return
            }


            if (file.size > 2 * 1024 * 1024) {
                this.$showAlert('Файл слишком большой. Максимальный размер - 2MB', 'error')
                return
            }

            this.form.logoFile = file

            const reader = new FileReader()
            reader.onload = (e) => {
                this.avatarPreview = e.target.result
            }
            reader.readAsDataURL(file);
        },
        deleteTeam() {
            const accept = window.confirm('Вы уверены что хотите удалить команду?');
            if (accept) {
                axios.delete(`/team/${this.team.id}/delete`)
                    .then(() => {
                        this.$router.push({
                            name: 'profile',
                            params: {
                                'user': this.user.name
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error deleting team:', error);
                    });
            }
        },
        
        
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    }
}
</script>
<style scoped>
.settings-container {
    display: flex;
    flex-direction: column;
    gap: 50px;
    width: 60%;
}

.settings {
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
    gap: clamp(5px, 2.5vw, 40px);
    margin-top: 80px;
    width: 100%;
    max-width: 1600px;
}

.settings-block {
    display: flex;
    flex-direction: column;
    gap: 25px;
    width: 25%;
    max-width: 500px;
    color: #FFFFFF;
}

.container-team-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
}

.cointainer-general-info {
    flex: 1;
    color: #F8F8F8;
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.team-image {
    position: relative;
    width: 200px;
    padding-top: 200px;
    border-radius: 50%;
    height: 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0px 0px 3px #4B4F53;
    background-color: #101112;
}

.img-logo {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.btn-update-image {
    cursor: pointer;
    width: 100%;
    padding: 10px 20px;
    background: none;
    border: 1px solid #343A40;
    border-radius: 10px;
    color: #F8F8F8;
    font-size: 18px;
    font-weight: 500;
}

.btn-update-image:hover {
    background-color: #343A4050;
}


.team-name {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.input-settings {
    color: #F8F8F8;
    width: 65%;
    padding: 10px 15px;
    font-size: 18px;
    background: none;
    border: 1px solid #343A40;
    border-radius: 5px;
}

.team-delete-container {
    color: #F8F8F8;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
}

.title-delete,
.label-input {
    font-weight: 500;
}

.ps {
    margin-top: 10px;
    font-size: 17px;
}

.delete-team-btn {
    cursor: pointer;
    white-space: nowrap;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    background: none;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    color: #FF0E0E;
    height: fit-content;
    align-self: center;
}

.delete-team-btn:hover {
    background-color: #FF0E0E20;
}

.save-team-btn {
    margin-top: -50px;
    cursor: pointer;
    border: 1px solid #EDB200;
    border-radius: 5px;
    background: none;
    color: #F8F8F8;
    font-size: 16px;
    width: fit-content;
    padding: 10px 15px;
}

.save-team-btn:hover {
    background: #EDB20050;
}
</style>