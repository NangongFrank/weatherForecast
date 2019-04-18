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
			<view class="m-ft-btn">注册</view>
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
				message: "",
				pwd: "",
				rePwd: "",
			}
		},
	}
</script>

<style lang="less" scoped>
	@import "../../static/lesslib/loginAbout.less";	
</style>
