<template>
	<view class="m-setting">
		<view class="m-hd">
			<navigator class="iconfont" url="./home">&#xe65a;</navigator>
			<text>设置</text>
		</view>
		<view class="m-bd">
			<view class="m-bd-item bdb">
				<view class="m-bd-item-left">
					<text class="title">天气提示信息提醒</text>
					<text class="tip">开启即显示天气提示信息</text>
				</view>
				<view class="m-bd-item-right">
					<switch :checked="info" color="#1296db" @change="infoChange"/>
				</view>
			</view>
			<view class="m-bd-item bdb">
				<view class="m-bd-item-left">
					<text class="title">天气预警提醒</text>
					<text class="tip">开启即显示预警信息</text>
				</view>
				<view class="m-bd-item-right">
					<switch :checked="warning" color="#1296db" @change="warningChange"/>
				</view>
			</view>
			<view class="m-bd-item bdb" @tap="handlerSwitch">
				<view class="m-bd-item-left">
					<text class="title">温度单位</text>
					<text class="tip iconfont" v-if="nowState">&#xe6ef;</text>
					<text class="tip iconfont" v-else>&#xe6ee;</text>
				</view>
			</view>
			<view class="m-bd-item bdb" @tap="handlerSwitch">
				<view class="m-bd-item-left">
					<text class="title">版本</text>
					<text class="tip">v1.0</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		methods: {
			infoChange({detail}) {
				uni.setStorage({
					key: "isInfo",
					data: {isInfo: detail.value},
					success() {
						uni.showToast({
							title: "设置成功!",
							duration: 1200,
							mask: true,
						})
					}
				})
			},
			warningChange({detail}) {
				uni.setStorage({
					key: "isWarning",
					data: {isWarning: detail.value},
					success() {
						uni.showToast({
							title: "设置成功!",
							duration: 1200,
							mask: true,
						})
					}
				})
			},
		},
		data() {
			return {
				info: false,
				warning: false,
			}
		},
		onLoad() {
			var vm = this
			uni.getStorage({
				key: "isInfo",
				success({data}) {
					vm.info = data.isInfo
				},
				fail() {
					vm.report = false
				},
			})
			uni.getStorage({
				key: "isWarning",
				success({data}) {
					vm.warning = data.isWarning
				},
				fail() {
					vm.warning = false
				},
			})
		},
	}
</script>

<style lang="less" scoped>
	@import "../config.less";
	.bdb {
		border-bottom: 2upx solid @c-bd;
	}
	.m-hd {
		color: @c-hd;
		@{ai}: center;
		border-bottom: 2upx solid @c-bd;
		text {
			@{fs}: 46upx;
		}
		.iconfont {
			margin-right: 80upx;
			padding: 10upx;
		}
	}
	.m-bd {
		padding: 0 36upx;
		&-item {
			display: flex;
			@{ai}: center;
			@{jc}: space-between;
			padding: 30upx 0 30upx 40upx;
			&-left {
				display: flex;
				@{fd}: column;
			}
			.title {
				@{fs}: 44upx;
				color: @font-glay;
			}
			.tip {
				@{fs}: 36upx;
				color: @font-small-glay;
			}
			.iconfont {
				@{fs}: 46upx;
			}
		}
	}
</style>
