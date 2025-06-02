import Cookies from 'js-cookie';

const authStore = {
    namespaced: true,
    state: {
        isAuthenticated: false,
        user: null,
    },
    mutations: {
        setUser(state, user) {
            state.isAuthenticated = true;
            state.user = user;
            Cookies.set('user', JSON.stringify(user), { expires: 3}); // 3 дня
            Cookies.set('isAuthenticated', 'true', { expires: 3 });
        },
        logout(state) {
            state.isAuthenticated = false;
            state.user = null;

            Cookies.remove('user');
            Cookies.remove('isAuthenticated');
        },

        initializeStore(state) {
            const userCookie = Cookies.get('user');
            const isAuthenticatedCookie = Cookies.get('isAuthenticated');

            if (userCookie) {
                state.user = JSON.parse(userCookie);
                state.isAuthenticated = isAuthenticatedCookie === 'true';
            }
        },
        updateAvatar(state, newAvatarPath) {
            if (state.user) {
                state.user.avatar = newAvatarPath;
                Cookies.set('user', JSON.stringify(state.user), { expires: 259200 / 86400 });
            }
        },
    },
    actions: {
    },
    getters: {
        isAuthenticated: (state) => state.isAuthenticated,
        user: (state) => state.user,
    },
};

export default authStore;