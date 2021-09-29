import http from "../helpers/http";

export const loginUser = async (payload = {}) => {

    try {
        const res = await http.route('api/login').post({email : "takeathome@frisbo.ro", password  : "TakeAtHomeFris2021"});
        if (res && res.data.access_token) {
            sessionStorage.setItem('token', res.data.access_token);
            //todo: use store dispatch
            window.location.reload();
        }
    } catch (e) {
        console.log(e);
    }

};

export const setUser = (payload) => {
    return {
        payload,
        type: 'SET_USER'
    };
};


export default {
    loginUser,
};
