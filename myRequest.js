import {myServer} from "host"

function req(data, sucfunc) {
	uni.request({
		url: myServer,
		data,
		method: "post",
		header: {
			"Content-Type": "application/x-www-form-urlencoded"
		},
		success({data}) {
			sucfunc(data)
		},
		fail() {
			uni.showToast({
				title: "获取服务器数据失败",
				duration: 1200,
				icon: "none",
			})
		}
	})
}
export default req