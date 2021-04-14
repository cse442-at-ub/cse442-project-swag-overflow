import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _82ad92d8 = () => interopDefault(import('..\\pages\\associates.vue' /* webpackChunkName: "pages/associates" */))
const _75adcfba = () => interopDefault(import('..\\pages\\profile.vue' /* webpackChunkName: "pages/profile" */))
const _b71dd8e8 = () => interopDefault(import('..\\pages\\scheduler.vue' /* webpackChunkName: "pages/scheduler" */))
const _49f3ae5b = () => interopDefault(import('..\\pages\\updates.vue' /* webpackChunkName: "pages/updates" */))
const _695a5ca3 = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages/index" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/associates",
    component: _82ad92d8,
    name: "associates"
  }, {
    path: "/profile",
    component: _75adcfba,
    name: "profile"
  }, {
    path: "/scheduler",
    component: _b71dd8e8,
    name: "scheduler"
  }, {
    path: "/updates",
    component: _49f3ae5b,
    name: "updates"
  }, {
    path: "/",
    component: _695a5ca3,
    name: "index"
  }],

  fallback: false
}

export function createRouter (ssrContext, config) {
  const base = (config.app && config.app.basePath) || routerOptions.base
  const router = new Router({ ...routerOptions, base  })

  // TODO: remove in Nuxt 3
  const originalPush = router.push
  router.push = function push (location, onComplete = emptyFn, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort)
  }

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    return resolve(to, current, append)
  }

  return router
}
