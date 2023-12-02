const form = document.forms[0]

/* IF SUBMIT BUTTON IS CLICKED */
form.onsubmit = async function (e) {
    e.preventDefault()

    const pwd = document.getElementById('pwd')
    const name = document.getElementById('name')
    const email = document.getElementById('email')

    const url = '/api/v1/account/register'

    /* SEND POST REQUEST TO API */
    const PostRequest = await axios({
        method: 'POST',
        url: url,
        data: JSON.stringify({
            fullname: name.value,
            email: email.value,
            password: pwd.value
        }),
        headers: {
            'Content-type': 'multipart/form-data'
        },
        baseURL: 'http://localhost/projects/php_router'
    });

    try {
        if (PostRequest.status >= 200 && PostRequest.status <= 299) {
            const jwt = await PostRequest.data;

            window.location.href = '/projects/php_router/profile/' + jwt.data.user_id;
        }
    } catch (error) {
        console.log(error);
    }
}