<template>
	<view>
		<login-header title="重置密码" />
		<view class="padding-space"></view>
		<view class="m-ct">
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
					<input v-model="pwd" placeholder="输入新密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="rePwd" placeholder="确认新密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
			</view>
		</view>
		<view class="m-ft">
			<view class="m-ft-btn" @tap="jumpToUser">确定</view>
			<view class="m-ft-tip">若忘记手机号和用户名，请联系管理员协助修改密码</view>
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
				pwd: "",
				rePwd: "",
				phone: "",
				name: "",
			}
		},
		onLoad() {

		},
		methods: {
			jumpToUser() {
				let vm = this,
					pwd = vm.pwd,
					rePwd = vm.rePwd
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
					f: "resetUserPwd",
					c: "user",
					pwd,
					phone: vm.phone,
					name: vm.name,
					id: vm.id
				}, res => {
					if(!res.state) {
						uni.showToast({
							title: "改密失败",
							duration: 1200,
							icon: "none"
						})
					} else {
						uni.showToast({
							title: "密码修改成功",
							duration: 1200,
						})
						uni.removeStorage({
							key: "userInfo",
						})
						setTimeout(() => {
							uni.reLaunch({
								url: "Login"
							})
						}, 1100)
					}
				})
				uni.reLaunch({
					url: "./Login"
				})
			}
		}
	}
</script>

<style lang="less" scoped>
	@import "../../static/lesslib/loginAbout.less";
	.m-ft {
		&-tip {
			margin-top: 40upx;
			@{fs}: 24upx;
			color: @font-small-black;
		}
	}
</style>
