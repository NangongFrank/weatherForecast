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
					<view class="title" @tap="thisSideInfo(value.code)" 
					v-text="value.province + ' - ' + value.city + ' - ' + value.area"></view>
					<view class="box">
						<view class="wd">
							<text v-text="value.temp ? value.temp : ''"></text>
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
					<view class="title" @tap="jumpToNowSide(nowSideWeather.cityIds)" v-text="nowSideWeather.side"></view>
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
				userId: "",
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
			thisSideInfo(tag) {
				uni.reLaunch({
					url: "./home?q=" + tag + "&n=" + "notHome"
				})
			},
			jumpToNowSide(tag) {
				uni.reLaunch({
					url: "./home?q=" + tag + "&n=" + "home"
				})
			},
			deleteItem(value, index) {
				var vm = this
				uni.showModal({
					content: "确定删除城市?",
					title: "提示",
					confirmColor: "rgb(102, 177, 255)",
					complete({confirm}) {
						if(confirm) {
							if(!vm.userId) {
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
								vm.$myreq({
									f: "rmUserSide",
									c: "user",
									id: value.id,
								}, res => {
									if(!res.data) {
										uni.showToast({
											title: "地区删除失败！",
											duration: 1000,
											icon: "none",
											mask: true,
										})
									} else {
										uni.showToast({
											title: "地区删除成功",
											duration: 1200,
											mask: true,
										})
										setTimeout(() => {
											vm.initSideList()
										}, 300)
									}
								})
							}
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
				let vm = this
				uni.showLoading({
					title: "数据请求中...",
					mask: true,
				})
				function saveTemp() {
					return new Promise((resolve, reject) => {
						if(!vm.userId) {
							reject(value.code)
						} else {
							resolve(value.id)
						}
					})
				}
				vm.$req('get', weatherAPI, {cityIds: value.code}, res => {
					uni.hideLoading()
					res = res.value[0].realtime.temp
					value.temp = res
					saveTemp().then(id => {
						vm.$myreq({
							f: "setSideTemp",
							c: 'user',
							temp: res,
							id,
						}, res => {
							if(!res.data) {
								uni.showToast({
									title: "地区温度更新失败!",
									mask: true,
									duration: 1000,
									icon: "none"
								})
							} else {
								uni.showToast({
									title: "地区温度已更新...",
									mask: true,
									duration: 1000
								})
								setTimeout(() => {
									vm.initSideList()
								}, 1000)
							}
						})
					}, sideCode => {
						return new Promise((resolve, reject) => {
							uni.getStorage({
								key: "sideList",
								success({data}) {
									resolve({data, sideCode})
								}
							})
						})
					}).then(({data, sideCode}) => {
						let len = data.length
						for(let i = 0; i < len; ++i) {
							if(data[i].code == sideCode) {
								data[i].temp = res
								break
							}
						}
						uni.setStorage({
							key: "sideList",
							data,
						})
					})
				})
			},
			reqNowSideWeather(tag) {
				var vm = this
				uni.showLoading({
					title: "数据请求中...",
					mask: true,
				})
				vm.$req('get', weatherAPI, {cityIds: tag.cityIds}, res => {
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
				}, err => {
					uni.hideLoading()
				})
			},
			initSideList() {
				let vm = this
				function lineDoing() {
					uni.getStorage({
						key: "nowSideWeather",
						success({data}) {
							vm.nowSideWeather = data
						}
					})
					return new Promise((resolve, reject) => {
						uni.getStorage({
							key: "userInfo",
							success({data}) {
								data = data.data
								vm.userId = data.code
								resolve(data.code)
							},
							fail() {
								reject()
							}
						})
					})
				}	
				lineDoing().then(id => {
					vm.$myreq({
						f: "getUserSides",
						c: "user",
						id,
					}, res => {
						vm.sideList = res.data
						uni.stopPullDownRefresh()
					})
				}, () => {
					uni.getStorage({
						key: "sideList",
						success({data}) {
							vm.sideList = data
						},
						complete() {
							uni.stopPullDownRefresh()
						},
					})
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
				padding-right: 30upx;
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
