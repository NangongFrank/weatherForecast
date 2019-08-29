<template>
	<view>
		<!-- <login-header title="登录"  show-register :back-path="'/pages/setting'"/> -->
		<!-- <view class="padding-space"></view> -->
		<view class="m-ct">
			<view class="m-ct-item">
				<view class="label">
					<view class="iconfont user"></view>
					<input v-model="loginCode" placeholder="用户名/手机号" placeholder-style="font-size: 28upx; color: #666" />
				</view>
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="pwd" placeholder="输入密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
			</view>
		</view>
		<view class="m-ft">
			<view class="m-ft-btn" @tap="login">登录</view>
			<navigator url="/pages/children/ResetPwd">忘记密码?</navigator>
			<navigator url="/pages/children/Register">前往注册</navigator>
		</view>
	</view>
</template>

<script>
	// import loginHeader from "@/components/LoginHeader"
	export default {
		// components: {
		// 	loginHeader,
		// },
		data() {
			return {
				loginCode: "",
				pwd: "",
			}
		},
		onPullDownRefresh() {
			this.initData()
		},
		methods: {
			login() {
				let vm = this,
					info = vm.loginCode,
					pwd = vm.pwd
				if(!info || !pwd) {
					uni.showToast({
						duration: 1200,
						title: "用户名和密码不可为空",
						icon: "none",
					})
					return
				}
				vm.$myreq({
					info,
					pwd,
					f: 'userlogin',
					c: 'user',
				}, res => {
					if(!res.state) {
						uni.showToast({
							title: "用户不存在",
							duration: 1200,
							mask: true,
							icon: "none"
						})
					} else {
						uni.showToast({
							title: "欢迎用户使用",
							duration: 1200,
							mask: true,
						})
						uni.setStorage({
							key: "userInfo",
							data: res,
							complete() {
								setTimeout(() => {
									uni.reLaunch({
										url: './../setting'
									})
								}, 1000)
							}
						})
					}
				})
			},
			initData() {
				let vm = this
				vm.pwd = ""
				vm.loginCode = ""
				uni.stopPullDownRefresh()
			}
		}
	}
</script>

<style lang="less" scoped>
	@import "../../static/lesslib/loginAbout.less";
	.nav {
		margin: 22upx 0 0;
		padding: 0 20upx;
		display: flex;
		@{bgc}: #fff;
		view {
			flex: 1;
			display: flex;
			@{ai}: center;
			@{jc}: center;
			color: @font-black;
			padding: 16upx 0;
			border-bottom: 2upx solid transparent;
		}
		.active {
			border-color: @orange;
			color: @orange;
		}
	}
</style>
