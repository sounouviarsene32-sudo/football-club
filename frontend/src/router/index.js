import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'

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
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
