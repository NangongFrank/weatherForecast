<template>
	<view class="m-home" :style="{'background-color': 'rgba(21, 21, 21,' + opacity + ')'}">
		<view class="m-bg">
			<image class="bgi" :src="bgImg" mode="widthFix"></image>
		</view>
		<view class="m-hd" :style="{'background-color': 'rgba(21, 21, 21,' + (opacity + 0.2) + ')'}">
			<navigator class="iconfont ml" url="./sideList">&#xe6f9;</navigator>
			<view class="m-hd-center">
				<text class="iconfont" v-if="isNowSide">&#xe61f;</text>
				<text v-text="nowSide"></text>
			</view>
			<navigator class="iconfont mr" url="./setting">&#xe638;</navigator>
		</view>
		<view class="space-padding"></view>
		<view class="m-ct">
			<view class="ad-if">
				<view class="ad-if-ct" v-if="isInfo">
					<view class="ad-if-ct-item"
					v-for="(value, index) in adviceList"
					:key="index">
						<uni-tag inverted="true"
						:text="value.name" 
						@click="getMore(value.content)"></uni-tag>
						<text class="tip" v-text="value.level"></text>
					</view>
				</view>
			</view>
			<text class="title-public">今日天气</text>
			<view class="m-today">
				<view class="m-today-hd">
					<view class="m-today-hd-wendu">
						<text class="iconfont" :class="['wh'+ toDay.img]"></text>
						<text v-text="toDay.weather"></text>
					</view>
					<view class="m-today-hd-time">
						<text>最近更新时间</text>
						<text v-text="toDay.time"></text>
					</view>
				</view>
				<view class="m-today-ct">
					<view class="m-today-ct-wendu">
						<text class="iconfont wendu"></text>
						<text v-text="toDay.temp"></text>
						<text class="iconfont c"></text>
					</view>
					<view class="m-today-ct-wind">
						<text class="iconfont wind"></text>
						<text v-text="toDay.wD"></text>
						<text v-text="toDay.wS"></text>
					</view>
					<view class="m-today-ct-ziwaxian">
						<text class="iconfont ziwaixian"></text>
						<text v-text="toDay.ziwaixian"></text>
					</view>
				</view>
			</view>
			<text class="title-public">未来一天内</text>
			<view class="m-feature">
				<view class="pull-time">
					<text>更新时间</text>
					<text class="time-pull" v-text="featurePullTime"></text>
				</view>
				<view class="m-feature-item"
				v-for="(value, index) in featureList"
				:key="index">
					<view class="weather">
						<text class="iconfont" :class="['wh' + value.img]"></text>
					</view>
					<view class="temperature">
						<view class="highest-temperature">
							<text class="iconfont high-temp"></text>
							<text v-text="value.highestTemperature"></text> 
							<text class="iconfont c"></text>
						</view>
						<view class="lowerest-temperature">
							<text class="iconfont lower-temp"></text>
							<text v-text="value.lowerestTemperature"></text> 
							<text class="iconfont c"></text>
						</view>
					</view>
					<view class="time-aria">
						<view class="start-time">
							<text class="iconfont time-start"></text>
							<text v-text="value.startTime"></text> 
						</view>
						<view class="end-time">
							<text class="iconfont time-end"></text>
							<text v-text="value.endTime"></text> 
						</view>
					</view>
				</view>
			</view>
			<text class="title-public">一周天气</text>
			<view class="week">
				<view class="li"
				v-for="(value, index) in weekReport"
				:key="index">
					<text class="week-day" v-text="value.week"></text>
					<view class="weather">
						<text class="iconfont" :class="['wh' + value.img]"></text>
						<text v-text="value.weather"></text>
					</view>
					<view class="day">
						<text class="tip">白天</text>
						<text class="day-c iconfont c" v-text="value.temp_day_c"></text>
						<text class="day-f iconfont f" v-text="value.temp_day_f"></text>
						<text v-text="sun_rise_time"></text>
					</view>
					<view class="night">
						<text class="tip">夜晚</text>
						<text class="night-c iconfont c" v-text="value.temp_night_c"></text>
						<text class="night-f iconfont f" v-text="value.temp_night_f"></text>
						<text v-text="sun_down_time"></text>
					</view>
					<text class="date" v-text="dealYear(value.date)"></text>
				</view>
			</view>
		</view>
		<view class="space-padding"></view>
	</view>
</template>

<script>
	import {sideAPI, weatherAPI} from "host"
	import uniTag from '@/components/uni-tag/uni-tag'
	export default {
		components: {
			uniTag,
		},
		onLoad({q, n}) {
			let vm = this
			vm.initSetting()
			if(!q) {
				vm.initNowSide()
			} else {
				vm.q = q
				vm.getSideNow(q)
			}
			if(!n) {
				vm.isNowSide = true
			} else if(n == "home"){
				vm.isNowSide = true
			} else {
				vm.isNowSide = false
			}
		},
		onPageScroll({scrollTop}) {
			if(scrollTop < 50) {
				this.opacity = 0
				return
			} else {
				scrollTop /= 50
				scrollTop ^= 0
				if(scrollTop < 7) {
					this.opacity = scrollTop / 10
				} else {
					this.opacity = 0.6
				}
			}
		},
		methods: {
			getSideNow(cityIds) {
				var vm = this
				vm.$req("get", weatherAPI, {
					cityIds,
				}, r => {
					// 界面效果展示
					r = r.value[0]
					vm.adviceList = r.indexes
					vm.weekReport = r.weathers
					vm.toDay = r.realtime
					vm.nowSide = r.provinceName + " - " + r.city
					vm.featurePullTime = r.weatherDetailsInfo.publishTime
					vm.featureList = r.weatherDetailsInfo.weather3HoursDetailsInfos
					uni.stopPullDownRefresh()
				})
			},
			initSetting() {
				var vm = this
				uni.getStorage({
					key: "isInfo",
				}).then(() => {
					this.isInfo = true
				}).catch(() => {
					this.isInfo = false
				})
				uni.getStorage({
					key: "isWarning",
				}).then(([err, {data}]) => {
					vm.isWarning = data.isInfo
				})
			},
			getMore(value) {
				uni.showToast({
					title: value,
					icon: 'none',
					duration: 3000,
				})
			},
			dealYear(tag) {
				return tag.replace(/^\d{4}-/, '')
			},
			randomImg() {
				let url = "/static/assets/bg",
					name = ".jpg",
					num = Math.random() * 13 ^ 0
				if(!num) {
					num = 1
				}
				num = num > 9 ? num : "0" + num
				url = url + num + name
				this.bgImg = url
			},
			initNowSide() {
				uni.showLoading({
					title: '数据获取中...'
				})
				let vm = this
				vm.isNowSide = true
				function lineDoing() {
					return new Promise((resolve, reject) => {
						uni.getLocation({
							type:"gcj02",
							success(res) {
								resolve(res.latitude + "," + res.longitude)
							},
							fail() {
								uni.showToast({
									title: "定位失败，检查是否已开启定位",
									icon: "none",
									duration: 2000,
								})
								uni.hideLoading()
								uni.stopPullDownRefresh()
							}
						})
					})
				}
				lineDoing().then(location => {
					return new Promise((resolve, reject) => {
						vm.$req("get", sideAPI, {
							location,
							ak: "nkXgBLARGGG5RfZAhfjVKbGCwhIl30P5",
							output: "json",
						}, ({result}) => {
							resolve(result)
						}, err => {
							 uni.showToast({
							 	title: "获取当前位置失败",
								icon: "none",
								duration: 15000,
							 })
							 uni.hideLoading()
							 uni.stopPullDownRefresh()
						})
					}) 
				}).then(result => {
					let side = result.addressComponent,
						province = side.province,
						city = side.city
					vm.nowSide = province + " - " + city
					province = province.substr(0, 2)
					city = city.substr(0, 2)
					return new Promise((resolve, reject) => {
						vm.$myreq({
							f: "getCityCode",
							c: "side",
							province,
							city,
						}, res => {
							resolve(res.data.code)
						})
					})
				}).then(cityIds => {
					vm.$req("get", weatherAPI, {
						cityIds,
					}, r => {
						// 界面效果展示
						r = r.value[0]
						vm.adviceList = r.indexes
						vm.weekReport = r.weathers
						vm.toDay = r.realtime
						vm.featurePullTime = r.weatherDetailsInfo.publishTime
						vm.featureList = r.weatherDetailsInfo.weather3HoursDetailsInfos
						r = r.realtime
						let arr = vm.nowSide.split(" - "),
							reg = /\W$/
						arr = arr.map(value => {
							return value.replace(reg, "")
						})
						uni.setStorage({
							key: "nowSideWeather",
							data: {
								temp: r.temp,
								side: arr.join(" - "),
								cityIds,
							},
						})
						uni.hideLoading()
						uni.stopPullDownRefresh()
					})
				})
			},
		}, 
		onPullDownRefresh() {
			let vm = this
			if(!vm.q) {
				vm.initNowSide()
			} else {
				vm.getSideNow(vm.q)
			}
			vm.randomImg()
		},
		data() {
			return {
				nowSide: "",
				initObj: {},
				bgImg: "/static/assets/bg01.jpg",
				opacity: 0,
				lock: false,
				adviceList: [],
				weekReport: [],
				toDay: {},
				featurePullTime: "",
				featureList: [],
				isInfo: false,
				isWarning: true,
				isNowSide: true,
				q: "",
				n: "",
			}
		},
	}
</script>

<style lang="less" scoped>
	@import "../config.less";
	scroll-view {
		width: 750upx;
		white-space: nowrap;
	}
	.m-ct {
		display: inline-block;
		width: 750upx;
	}
	.week {
		column-count: 7;
		column-width: 2upx;
		.li {
			display: flex;
			@{fd}: column;
			@{ai}: center;
			.date {
				@{fs}: 20upx;
			}
			.week-day {
				@{fs}: 24upx;
				color: #fff;
			}
			.weather {
				display: flex;
				margin-top: 14upx;
				@{fd}: column;
				@{ai}: center;
				color: #eee;
				.iconfont {
					@{fs}: 46upx;
				}
				@{fs}: 24upx;
			}
			.day,
			.night {
				display: flex;
				@{fd}: column;
				color: #ddd;
				margin-top: 20upx;
				.tip {
					@{fs}: 20upx;
				}
				text {
					display: flex;
					@{fd}: row-reverse;
					@{ai}: baseline;
					@{fs}: 24upx;
				}
			}
		}
	}
	.title-public {
		@{fs}: 42upx;
		font-weight: 900;
		color: #49afdc;
		display: inline-block;
		padding: 10upx 20upx;
		margin: 50upx 0 20upx;
	}
	.m-feature {
		.pull-time {
			display: flex;
			color: #ddd;
			@{fs}: 32upx;
			text {
				margin: 10upx 20upx;
			}
			.time-pull {
				@{fs}: 36upx;
				color: #eee;
			}
		}
		&-item {
			margin: 18upx 0;
			display: flex;
			color: #fff;
			@{fs}: 32upx;
			@{jc}: space-around;
			@{ai}: center;
			.iconfont {
				@{fs}: 42upx;
			}
		}
	}
	.m-today {
		display: flex;
		&-hd {
			color: #eee;
			padding-left: 20upx;
			.iconfont {
				@{fs}: 100upx;
				margin-right: 40upx;
			}
			&-time {
				@{fs}:24upx;
				padding: 20upx 0;
				display: flex;
				@{fd}: column;
			}
		}
		&-ct {
			flex: 1;
			display: flex;
			@{ai}: flex-end;
			@{jc}: space-around;
			text {
				@{fs}: 36upx;
				color: #eee;
			}
		}
	}
	.ad-if {
		position: relative;
		height: 500upx;
		&-ct {
			position: absolute;
			right: 30upx;
			top: 40upx;
			&-item {
				display: flex;
				margin: 10upx 0;
				@{jc}: space-between;
				width: 340upx;
				@{ai}: baseline;
				.tip {
					@{fs}: 26upx;
					padding: 6upx 10upx;
					@{bdra}: 10upx;
					@{bgc}: #52bff0;
					color: #eee;
				}
			}
		}
	}
	.m-home {
		position: relative;
		left: 0;
		top: 0;
		width: 100%;
	}
	.m-bg {
		position: fixed;
		z-index: -1;
		top: 0;
		left: 0;
		image {
			width: 750upx;
		}
	}
	.m-hd {
		width: 750upx;
		display: flex;
		@{jc}: space-between;
		@{ai}: baseline;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 10;
		.iconfont,
		text {
			color: #fff;
			@{fs}: 42upx;
		}
		padding: 60upx 0 20upx;
		navigator {
			@{fs}: 40upx;
			padding: 16upx;
		}
		.ml {
			margin-left: 40upx;
		}
		.mr {
			margin-right: 40upx;
		}
	}
</style>
