import {myServer} from "host"

function req(data, sucfunc) {
	uni.request({
		data,
		header: {
			"Content-Type": "x-www-form-urlencoded"
		},
		success({data}) {
			sucfunc(data)
		},
		fail() {
			console.log(arguments)
		}
	})
}
export default req