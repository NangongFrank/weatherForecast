function reqFunc(type, url, data, suc, err) {
	type = type.toLowerCase()	
	if(typeof err != "function") {
		err = error => {
			console.log(error)
		}
	}
	uni.request({
		type,
		url,
		data,
		success(res) {
			res = res.data
			suc(res)
		},
		error(e) {
			err(e)
		},
	})
}


export default reqFunc