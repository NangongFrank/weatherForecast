<template>
	<view class="m-add-side">
		<view class="m-hd">
			<navigator class="iconfont" url="../sideList">&#xe65a;</navigator>
			<input v-model="searchText" placeholder="输入城市名称" />
			<scroll-view scroll-y v-if="menuListState">
				<view class="m-hd-box"
				v-for="(value, index) in list"
				@tap="addItem(value, index)"
				:key="index">
					<text 
					:class="{bdt: index}" 
					v-text="value.province + value.city + value.area"></text>
				</view>
			</scroll-view>
		</view>
		<view class="nav">
			<text>热门城市</text>
		</view>
		<view class="m-bd">
			<view class="m-bd-item"
			v-for="(value, index) in hotCitys"
			:key="index"
			@tap="addItem(value, index)">
				<text v-text="value.province + value.city + value.area"></text>
			</view>			
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				hotCitys: [],
				list: [],
				searchText: "",
				menuListState: false,
			}
		},
		onPullDownRefresh() {
			this.initHotCitys()
			this.searchText = ""
		},
		onLoad() {
			this.initHotCitys()
		},
		watch: {
			searchText(newSide) {
				this.searchItem(newSide)
			},
		},
		methods: {
			initHotCitys() {
				let vm = this
				vm.$myreq({
					f: "getHotCitys",
					c: "side",
					r: "random"
				}, res => {
					vm.hotCitys = res.data
					uni.stopPullDownRefresh()
				})
			},
			searchItem(search) {
				let vm = this
				if(!search) {
					vm.menuListState = false
					return
				}
				vm.$myreq({
					f: "getAboutCity",
					c: 'side',
					search,
				}, res => {
					vm.list = res.data
					if(res.data.length > 0) {
						vm.menuListState = true
					} else {
						vm.menuListState = false
					}
				})
			},
			addToSideList(data) {
			},
			addItem(value, index) {
				
			},
		},
	}
</script>

<style lang="less" scoped>
	@import "../../config.less";
	.bdt {
		border-top: 2upx solid #ddd;
	}
	scroll-view {
		height: 600upx;
		position: absolute;
		left: 0;
		top: 146upx;
		z-index: 10;
		.m-hd-box {
			color: #333;
			@{bgc}: #fff;
			padding:  0 20upx;
			display: flex;
			text {
				padding: 12upx 8upx;
				display: flex;
				@{ai}: center;
				flex:1;
			}
		}
	}
	.m-bd {
		display: flex;
		@{fw}: wrap;
		justify-content: space-between;
		padding: 0 20upx;
		position: relative;
		view {
			width: 220upx;
			height: 40upx;
			margin: 12upx 0;
			@{bdra}: 25upx;
			display: flex;
			@{ai}: center;
			@{jc}: center;
			border: 2upx solid #ddd;
			@{fs}: 24upx;
			text {
				color: #333;
			}
		}
	}
	.m-hd {
		color: @c-hd;
		@{ai}: center;
		border-bottom: 2upx solid @c-bd;
		text {
			@{fs}: 36upx;
		}
		input {
			color: #333;
		}
		.iconfont {
			margin-right: 50upx;
			padding: 10upx;
		}
	}
	.nav {
		color: @brand-one;
		padding: 20upx;
		@{fs}: 32upx;
	}
</style>
