import axios from 'axios'
import qs from "qs"

const reqHost = "http://192.168.1.142:8888/wf_php/index.php"
//const reqHost = "http://192.168.1.142/wf_php/index.php"


function req(type, data, success, err) {
    type = type.toLowerCase()
    const func = axios.create({
        baseURL: reqHost,
    })
    let options
    if(type == "get") {
        options = {
            params: data,
        }
    } else if(type == "post") {
        options = {
            method: 'post',
            data: qs.stringify(data),
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }
    }
    if(typeof err != "function") {
        err = new Function()
    }
    func(options).then(res => {
        res = res.data
        success(res)
    }).catch(error => {
        err(error)
    })
}

export default req
