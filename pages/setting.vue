<template>
	<view class="m-setting">
		<view class="m-hd">
			<navigator class="iconfont" url="./home">&#xe65a;</navigator>
			<text>设置</text>
		</view>
		<view class="m-bd">
			<view class="m-bd-item bdb">
				<view class="m-bd-item-left">
					<text class="title">警告信息提醒</text>
					<text class="tip">开启即可接受到当前位置预警通知</text>
				</view>
				<view class="m-bd-item-right">
					<switch :checked="report" color="#1296db" @change="wraningChange"/>
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
			wraningChange({detail}) {
				uni.setStorage({
					key: "isShowWarning",
					data: {isShowWarning: detail.value},
					complete(e) {
						console.log(e)
					}
				})
			},
		},
		data() {
			return {
				report: false,
			}
		},
		onLoad() {
			var vm = this
			uni.getStorage({
				key: "isShowWarning",
				success({data}) {
					vm.report = data.isShowWarning
				},
				fail() {
					vm.report = false
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
