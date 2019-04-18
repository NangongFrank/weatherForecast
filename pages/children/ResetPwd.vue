<template>
	<view>
		<login-header title="重置密码" />
		<view class="padding-space"></view>
		<view class="m-ct">
			<view class="m-ct-item">
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="pwd" placeholder="输入密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
				<view class="label">
					<view class="iconfont pwd"></view>
					<input v-model="rePwd" placeholder="确认密码" placeholder-style="font-size: 28upx; color: #666" password/>
				</view>
			</view>
		</view>
		<view class="m-ft">
			<view class="m-ft-btn" @tap="jumpToUser">确定</view>
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
			}
		},
		methods: {
			jumpToUser() {
				uni.reLaunch({
					url: "./Login"
				})
			}
		}
	}
</script>

<style lang="less" scoped>
	@import "../../static/lesslib/loginAbout.less";	
</style>
