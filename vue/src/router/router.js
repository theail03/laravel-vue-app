import { createRouter, createWebHistory } from "vue-router";
import Matrices from "../views/Matrices.vue";
import MatrixView from "../views/MatrixView.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import ErrorPage from "../views/ErrorPage.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import AuthLayout from "../components/AuthLayout.vue";
import store from "../store/store";
import DashboardWrapper from "../views/DashboardWrapper.vue";

const routes = [
  {
    path: "/",
    redirect: "/matrices/dashboard",
    component: DefaultLayout,
    children: [
      { path: "/matrices/dashboard", name: "MatricesDashboard", component: DashboardWrapper },
      { path: "/matrices", name: "Matrices", component: Matrices, props: { publicMode: false }, meta: { requiresAuth: true } },
      { path: "/matrices/public", name: "MatricesPublic", component: Matrices, props: { publicMode: true } },
      { path: "/matrices/create", name: "MatrixCreate", component: MatrixView, props: { editMode: true }, meta: { requiresAuth: true } },
      { path: "/matrices/:id", name: "MatrixEdit", component: MatrixView, props: { editMode: true }, meta: { requiresAuth: true } },
      { path: "/matrices/:id/view", name: "MatrixView", component: MatrixView, props: { editMode: false } },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    name: "Auth",
    component: AuthLayout,
    children: [
      {
        path: "/login",
        name: "Login",
        component: Login,
      },
      {
        path: "/register",
        name: "Register",
        component: Register,
      },
    ],
  },
  {
    path: '/:error', 
    name: 'Error',
    component: ErrorPage,
    props: true, 
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  await store.dispatch("getUser");
  const isUserDataSet = store.getters.isAuthenticated;

  if (to.meta.requiresAuth && !isUserDataSet) {
    next({ name: "MatricesDashboard" });
  } else {
    next();
  }
});

export default router;
