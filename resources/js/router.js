import { createRouter, createWebHistory } from 'vue-router';
import store from './store';
import Welcome from './pages/Welcome.vue';
import Dashboard from './pages/Dashboard.vue';
import Repository from './pages/Repository.vue';
import Registration from './pages/Registration.vue';
import Login from './pages/Login.vue';
import RepositoryCreate from './pages/RepositoryCreate.vue';
import TeamCreateView from './pages/TeamCreateView.vue';
import Profile from './pages/ProfileView.vue';
import TasksView from './pages/TasksView.vue';
import RepositoryTasks from './components/repository/RepositoryTasks.vue';
import TeamView from './pages/TeamView.vue';
import RepositorySettings from './components/repository/RepositorySettings.vue';

const isAuthenticated = (to, from, next) => {
    const authenticated = store.getters['authStore/isAuthenticated'];
    if (authenticated) {
        next(); 
    } else {
        next({ path: '/login' }); 
    }
};
const isNotAuthenticated = (to, from, next) => {
    const authenticated = store.getters['authStore/isAuthenticated'];
    const user = store.getters['authStore/user'];
    if (!authenticated) {
        next(); 
    } else {
        next({ path: `/${user.name}` }); 
    }
};
const isAdmin = (to, from, next) => {
    const user = store.getters['authStore/user'];
    if (user && user.role_id == 1) {
        next(); 
    } else {
        next({ path: '/' }); 
    }
};

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/registration',
            name: 'registration',
            component: Registration,
            beforeEnter: isNotAuthenticated
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            beforeEnter: isNotAuthenticated
        },
        {
            path: '/', 
            name: 'welcome',
            component: Welcome,
            beforeEnter: isNotAuthenticated
        },
        {
            path: '/dashboard', 
            name: 'dashboard',
            component: Dashboard,
            beforeEnter: isAuthenticated
        },
        {
            path: '/repository/create',
            name: 'createRepository',
            component: RepositoryCreate,
            beforeEnter: isAuthenticated
        },
        {
            path: '/team/create',
            name: 'createTeam',
            component: TeamCreateView,
            beforeEnter: isAuthenticated
        },
        {
            path: '/team/:team',
            name: 'team',
            component: TeamView,
            beforeEnter: isAuthenticated
        },
        {
            path: '/:user', 
            name: 'profile',
            component: Profile,
            beforeEnter: isAuthenticated
        },
        {
            path: '/:user/:repositoryName/:team/tasks',
            name: 'createTask',
            component: TasksView,
            beforeEnter: isAuthenticated
        },
        {
            path: '/:user/:repositoryName',
            name: 'repository',
            component: Repository,
            beforeEnter: isAuthenticated
        },
        {
            path: '/:user/:repositoryName/tasks',
            name: 'repositoryTeams',
            component: RepositoryTasks,
            beforeEnter: isAuthenticated
        },
        {
            // Доделать
            path: '/:user/:repositoryName/settings',
            name: 'repositorySettings',
            component: RepositorySettings,
            beforeEnter: isAuthenticated
        },



    ]
});

export default router;