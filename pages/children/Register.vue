<template>
	<view>
		<login-header title="注册" />
		<view class="padding-space"></view>
		<div class="m-ct">
			<view class="m-ct-item">
				<view class="label">
					<view class="iconfont user"></view>
					<input v-model="name" placeholder="输入用户名" placeholder-style="font-size: 28upx; color: #666" auto-focus />
				</view>
				<view class="label">
					<view class="iconfont phone"></view>
					<input type="number" v-model="phone" placeholder="输入手机号码" placeholder-style="font-size: 28upx; color: #666" />
				</view>
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="pwd" placeholder="输入密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="rePwd" placeholder="确认密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
			</view>
		</div>
		<view class="m-ft">
			<view class="m-ft-btn" @tap="register">注册</view>
			<navigator url="/pages/children/Login"  class="iconfont arrow-right">已有账号?前往登录</navigator>
		</view>
	</view>
</template>

<script>
	import loginHeader from "@/components/LoginHeader"
	export default {
		components: {
			loginHeader,
		},
		data() {
			return {
				name: "",
				phone: "",
				pwd: "",
				rePwd: "",
			}
		},
		methods: {
			register() {
				let vm = this,
					name = vm.name,
					phone = vm.phone,
					pwd = vm.pwd,
					rePwd = vm.rePwd
				if(!name) {
					uni.showToast({
						icon: "none",
						title: "用户名不可为空",
						duration: 1500,
					})
					return
				}
				if(!/^1\d{10}$/.test(phone)) {
					uni.showToast({
						icon: "none",
						title: "手机号码格式不正确",
						duration: 1500,
					})
					return
				}
				if(!pwd || !rePwd) {
					uni.showToast({
						icon: "none",
						title: "密码不可留空",
						duration: 1500,
					})
					return
				}
				if(pwd != rePwd) {
					uni.showToast({
						icon: "none",
						title: "两次密码不一致",
						duration: 1500,
					})
					return
				}
				if(pwd.length < 4) {
					uni.showToast({
						icon: "none",
						title: "密码过于简单",
						duration: 1500,
					})
					return
				}
				vm.$myreq({
					name,
					phone,
					pwd,
					f: "addrow",
					c: 'user',
				}, res => {
					if(!res.state) {
						uni.showToast({
							title: "请重试...",
							duration: 1500,
							icon: "none",
						})
					} else {
						uni.showToast({
							title: "用户注册成功",
							duration: 1100,
						})
						setTimeout(() => {
							uni.navigateTo({
								url: "Login",
							})
						}, 1000)
					}
				})
			},
		}
	}
</script>

<style lang="less" scoped>
	@import "../../static/lesslib/loginAbout.less";	
</style>
