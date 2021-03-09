import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _85714dd6 = () => interopDefault(import('../pages/profile.vue' /* webpackChunkName: "pages/profile" */))
const _6bcdb227 = () => interopDefault(import('../pages/scheduler.vue' /* webpackChunkName: "pages/scheduler" */))
const _dce59094 = () => interopDefault(import('../pages/updates.vue' /* webpackChunkName: "pages/updates" */))
const _4cec80be = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/profile",
    component: _85714dd6,
    name: "profile"
  }, {
    path: "/scheduler",
    component: _6bcdb227,
    name: "scheduler"
  }, {
    path: "/updates",
    component: _dce59094,
    name: "updates"
  }, {
    path: "/",
    component: _4cec80be,
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
