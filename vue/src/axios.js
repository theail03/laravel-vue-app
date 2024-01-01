import axios from "axios";
import router from "./router/router";
import { handleErrorResponse } from "./utils/errorHandler";

const axiosClient = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}`,
  withCredentials: true, // Include cookies in requests
})

axiosClient.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response.status === 401) {
    router.push({name: 'Login'});
  } else if (error.response.status === 403 || error.response.status === 404) {
    router.push({ name: 'Error', params: { error: error.response.status }});
  } else {
    handleErrorResponse(error);
  }
  throw error;
})

export default axiosClient;
