<template>
	<view class="wrapper">
		<view class="info-header">
			<view @tap="backPrev" class="action">
				<text class="iconfont arrow-left"></text>
			</view>
			<view class="box">
				<text v-text="title"></text>
			</view>
			<view class="space"
			v-if="showRegister"
			@tap="jumpToRegister">
				<text>注册</text>
			</view>
			<view class="space" v-else></view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			title: {
				type: String,
				default: "登录",
			},
			showRegister: {
				type: Boolean,
				default: false
			},
			backPath: {
				type: String,
				default: "",
			}
		},
		methods: {
			backPrev() {
				var vm = this
				if(!vm.backPath) {
					uni.navigateBack()
				} else {
					uni.navigateTo({
						url: vm.backPath,
					})
				}
				
			},
			jumpToRegister() {
				uni.navigateTo({
					url: "/pages/children/Register"
				})
			},
		}
	}
</script>

<style lang="less" scoped>
	@import '../static/config.less';
	@import "../static/less-color-theme/components.less";
	.wrapper {
		position: fixed;
		width: 100%;
		top: 0;
		left: 0;
		@{bgc}: #fff;
		z-index: 10;
	}
	.info-header {
		display: flex;
		@{ai}: center;
		height: 100upx;
		color: @font-black;
		.action,
		.space {
			width: 90upx;
			height: 100%;
			display:flex;
			@{ai}: center;
			@{jc}: center;
		}
		.box {
			margin: 0 auto;
		}
		.space {
			@{jc}: flex-start;
			text {
				@{fs}: 32upx;
			}
		}
	}
</style>
