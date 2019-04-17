<template>
	<div class="login">
		<form autocomplete="off" @keydown.enter="login">
			<div class="label-item"><h2>天气后台</h2></div>
			<div class="input-item">
				<el-input placeholder="用户名/手机号" v-model="code">
					<template slot="prepend">
						账号
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input type="password" placeholder="密码" v-model="pwd">
					<template slot="prepend">
						密码
					</template>
				</el-input>
			</div>
			<div class="input-submit"><el-button type="primary" @click="login">登录</el-button></div>
		</form>
	</div>
</template>
<script>
export default {
	methods: {
		login() {
			var vm = this,
				code = vm.code,
				pwd = vm.pwd,
				loading;
			if (!code || !pwd) {
				vm.$message({ message: '账号或密码不能为空', type: 'warning', duration: 1200 });
				return;
			}
			loading = vm.$loading({
				lock: true,
				text: '登录中...',
				spinner: 'el-icon-loading',
				background: 'rgba(0, 0, 0, 0.7)'
			});
			vm.$req(
				'post',
				{
					code,
					pwd,
					c: 'admin',
					f: 'adminlogin'
				},
				res => {
					if (res.state) {
						vm.$message({
							message: '验证通过',
							type: 'success',
							duration: 1200,
							onClose() {
								localStorage.adminInfo = JSON.stringify(res.data);
								vm.$router.push({ path: '/home' });
							}
						});
					} else {
						vm.$message({
							message: '账号或者密码错误',
							type: 'info',
							duration: 1200
						});
					}
					loading.close();
				}
			);
		}
	},
	data() {
		return {
			code: '',
			pwd: ''
		};
	}
};
</script>
<style lang="less" scoped>
@import './../config';
.el-select .el-input {
	width: 130px;
}
.input-with-select .el-input-group__prepend {
	@{bgc}: #fff;
}
.login {
	display: flex;
	@{ai}: center;
	@{jc}: center;
	@{bgc}: #2d3a4b;
	height: 100vh;
}
form {
	padding: 0.8em 1em;
	width: 400px;
}
.input-item {
	margin: 8px 0;
}
.input-submit {
	padding: 0 4em;
	display: flex;
	button {
		flex: 1;
		letter-spacing: 0.6em;
	}
}
.label-item {
	display: flex;
	@{jc}: center;
	color: #fff;
	padding: 0.6em 0;
	letter-spacing: 0.4em;
	font-family: '仿宋', '宋体', '微软雅黑';
	/* font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif; */
}
</style>
