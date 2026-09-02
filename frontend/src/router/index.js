import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import LoginView from '@/modules/auth/LoginView.vue'
import RegisterView from '@/modules/auth/RegisterView.vue'

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      {
        path: '',
        name: 'dashboard',
        component: () => import('@/modules/dashboard/DashboardView.vue'),
      },
      {
        path: 'teams',
        name: 'teams',
        component: () => import('@/modules/teams/TeamsView.vue'),
      },
      {
        path: 'teams/:id',
        name: 'team-detail',
        component: () => import('@/modules/teams/TeamDetailView.vue'),
      },
      {
        path: 'players',
        name: 'players',
        component: () => import('@/modules/players/PlayersView.vue'),
      },
      {
        path: 'matches',
        name: 'matches',
        component: () => import('@/modules/matches/MatchesView.vue'),
      },
      {
        path: 'competitions',
        name: 'competitions',
        component: () => import('@/modules/competitions/CompetitionsView.vue'),
      },
      {
        path: 'trainings',
        name: 'trainings',
        component: () => import('@/modules/trainings/TrainingsView.vue'),
      },
      {
        path: 'news',
        name: 'news',
        component: () => import('@/modules/news/NewsView.vue'),
      },
      {
        path: 'events',
        name: 'events',
        component: () => import('@/modules/events/EventsView.vue'),
      },
      {
        path: 'staff',
        name: 'staff',
        component: () => import('@/modules/staff/StaffView.vue'),
      },
    ],
  },
  {
    path: '/login',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'login',
        component: LoginView,
      },
    ],
  },
  {
    path: '/register',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'register',
        component: RegisterView,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (authStore.isAuthenticated && (to.name === 'login' || to.name === 'register')) {
    next({ name: 'dashboard' })
    return
  }

  const publicPages = ['login', 'register']
  const authRequired = !publicPages.includes(to.name)

  if (authRequired && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } })
    return
  }

  if (authRequired && authStore.token && !authStore.user) {
    await authStore.fetchUser()
    if (!authStore.isAuthenticated) {
      next({ name: 'login', query: { redirect: to.fullPath } })
      return
    }
  }

  next()
})

export default router
