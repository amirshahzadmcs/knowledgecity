import apiController from "./apiController";

export const registerUser = async (userData) => {
    try {
        const response = await apiController.post("/register", userData);
        return response.data;
    } catch (error) {
        return error.response.data;
    }
};

export const loginUser = async (credentials) => {
    try {
        const response = await apiController.post("/login", credentials);
        return response.data;
    } catch (error) {
        return error.response.data;
    }
};
