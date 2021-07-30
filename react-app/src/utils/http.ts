const get = (url: string) => {
    return fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(response => {
        if (!response.ok) {
            return response.json().then(res => {
                throw new Error(res.message)
            })
        }

        return response.json()
    })
}

const http = {
    get
}

export default http;