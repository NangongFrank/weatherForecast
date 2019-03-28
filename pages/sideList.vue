<template>
	<view>
		<view @tap="addSide" class="add-token">
			<text class="iconfont">&#xe6f9;</text>
		</view>
		<view class="m-hd">
			<navigator class="iconfont" url="./home">&#xe65a;</navigator>
			<text>已添加城市</text>
		</view>
		<view class="space-padding"></view>
		<view class="m-bd">
			<view class="m-bd-item"
			v-for="(value, index) in sideList"
			:key="index">
				<view class="delete-token"
				@tap="deleteItem(value, index)">
					<text class="iconfont">&#xe616;</text>
				</view>
				<view class="m-bd-item-ct">
					<view class="title" @tap="thisSideInfo(value, index)" v-text="value.name"></view>
					<view class="box">
						<view class="wd">
							<text v-text="value.temp"></text>
							<text class="tip iconfont" v-if="nowState">&#xe6ef;</text>
							<text class="tip iconfont" v-else>&#xe6ee;</text>
						</view>
						<text @tap="reqThisWeather(value, index)" class="iconfont">&#xe600;</text>
					</view>
				</view>
			</view>
			<view class="m-bd-item">
				<view class="delete-token">
					<text class="iconfont">&#xe61f;</text>
				</view>
				<view class="m-bd-item-ct">
					<view class="title" @tap="jumpToNowSide(nowSideWeather)" v-text="nowSideWeather.name"></view>
					<view class="box">
						<view class="wd">
							<text v-text="nowSideWeather.temp"></text>
							<text class="tip iconfont" v-if="nowState">&#xe6ef;</text>
							<text class="tip iconfont" v-else>&#xe6ee;</text>
						</view>
						<text @tap="reqNowSideWeather(nowSideWeather)" class="iconfont">&#xe600;</text>
					</view>
				</view>
			</view>
		</view>
		<view class="space-padding"></view>
	</view>
</template>

<script>
	import {weatherAPI} from "host"
	export default {
		data() {
			return {
				nowState: true,
				sideList: [],
				nowSideWeather: {},
			}
		},
		onPullDownRefresh() {
			this.initSideList()
		},
		methods: {
			addSide() {
				uni.navigateTo({
					url: "./children/AddSide"
				})
			},
			thisSideInfo(value, index) {
				uni.reLaunch({
					url: "./home?q=" + value.id + "&n=" + "notHome"
				})
			},
			jumpToNowSide(tag) {
				uni.reLaunch({
					url: "./home?q=" + tag.id + "&n=" + "home"
				})
			},
			deleteItem(value, index) {
				var vm = this
				uni.showModal({
					content: "确定删除城市" + value.name + "?",
					title: "提示",
					confirmColor: "rgb(102, 177, 255)",
					complete({confirm}) {
						if(confirm) {
							uni.getStorage({
								key: "sideList",
								success({data}) {
									data.splice(index, 1)
									uni.showToast({
										title: "删除成功!",
										duration: 1000,
									})
									uni.setStorage({
										key: "sideList",
										data,
										success() {
											vm.initSideList()
										},
									})
								}
							})
						} else {
							uni.showToast({
								title: "已取消删除",
								duration: 1000,
								icon: "none",
							})
						}
					}
				})
			},
			reqThisWeather(value, index) {
				var vm = this,
					obj
				uni.showLoading({
					title: "数据请求中...",
					mask: true,
				})
				uni.getStorage({
					key: "sideList",
					success({data}) {
						obj = data
					},
				})
				vm.$req('get', weatherAPI, {cityIds: value.id}, res => {
					res = res.value[0].realtime.temp
					value.temp = res
					obj.splice(index, 1, value)
					uni.setStorage({
						key: "sideList",
						data: obj,
						success() {
							vm.initSideList()
						},
						complete() {
							uni.hideLoading()
						}
					})
				})
			},
			reqNowSideWeather(tag) {
				var vm = this
				uni.showLoading({
					title: "数据请求中...",
					mask: true,
				})
				vm.$req('get', weatherAPI, {cityIds: tag.id}, res => {
					res = res.value[0].realtime.temp
					tag.temp = res
					uni.setStorage({
						key: "nowSideWeather",
						data: tag,
						success() {
							vm.initSideList()
						},
						complete() {
							uni.hideLoading()
						}
					})
				})
			},
			initSideList() {
				var vm = this,
					arr,
					len
				uni.getStorage({
					key: "sideList",
					success({data}) {
						vm.sideList = data
					},
					complete() {
						uni.stopPullDownRefresh()
					},
				})
				uni.getStorage({
					key: "nowSideWeather",
					success({data}) {
						vm.nowSideWeather = data
					}
				})
			},
		},
		onLoad() {
			this.initSideList()
		},
	}
</script>

<style lang="less" scoped>
	@import "../config.less";
	.m-bd {
		&-item {
			display: flex;
			padding: 30upx 20upx;
			@{ai}: center;
			.iconfont {
				width: 60upx;
				height: 60upx;
				display: flex;
				@{jc}: center;
				@{ai}: center;
				@{fs}: 50upx;
				color: #ccc;
			}
			.delete-token {
				padding: 0 20upx;
				width: 80upx;
			}
			.box {
				width: 200upx;
				display: flex;
				@{ai}: center;
			}
			.title {
				display: flex;
				@{ai}: center;
				flex: 1;
			}
			.wd {
				display: flex;
				width: 120upx;
				@{ai}: center;
				padding: 20upx 0;
			}
			.tip {
				color: @brand-one;
			}
			&-ct {
				border-bottom: 2upx solid #ddd;
				display: flex;
				flex: 1;
				@{jc}: space-between;
			}
		}
	}
	.add-token {
		display: flex;
		@{ai}: center;
		@{jc}: center;
		@{bdra}: 50%;
		color: #fff;
		@{bgi}: linear-gradient(@brand-four, @brand-five, @brand-four, @brand-three);
		width: 100upx;
		height: 100upx;
		position: fixed;
		z-index: 10;
		bottom: 70upx;
		right: 40upx;
	}
	.m-hd {
		color: @c-hd;
		@{ai}: center;
		border-bottom: 2upx solid @c-bd;
		position: fixed;
		@{bgc}: #fff;
		top: 0;
		left: 0;
		width: 100%;
		text {
			@{fs}: 46upx;
			font-weight: 900;
		}
		.iconfont {
			margin-right: 80upx;
			padding: 10upx;
		}
	}
</style>
