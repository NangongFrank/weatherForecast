<template>
	<view>
		<login-header title="登录"  show-register :back-path="'/pages/setting'"/>
		<view class="padding-space"></view>
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
			<view class="m-ft-btn">登录</view>
			<navigator url="./ResetPwd">忘记密码?</navigator>
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
				loginCode: "",
				pwd: "",
				phone: "",
				message: "",
			}
		},
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
