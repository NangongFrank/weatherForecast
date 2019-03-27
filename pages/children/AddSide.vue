<template>
	<view class="m-add-side">
		<view class="m-hd">
			<navigator class="iconfont" url="../sideList">&#xe65a;</navigator>
			<input type="text" @input="searchItem" v-model="searchText" placeholder="输入城市名称" />
			<scroll-view scroll-y v-if="menuListState">
				<view class="m-hd-box"
				v-for="(value, index) in list"
				@tap="addItem(value, index)"
				:key="index">
					<text 
					:class="{bdt: index}" 
					v-text="value.name"></text>
				</view>
			</scroll-view>
		</view>
		<view class="nav">
			<text>热门城市</text>
		</view>
		<view class="m-bd">
			<view class="m-bd-item"
			v-for="(value, index) in citys"
			:key="index"
			@tap="addItem(value, index)">
				<text v-text="value.name"></text>
			</view>			
		</view>
	</view>
</template>

<script>
	import {Districts} from "../../static/districts"
	export default {
		data() {
			return {
				citys: [],
				list: [],
				searchText: "",
				menuListState: false,
			}
		},
		onPullDownRefresh() {
			this.initHostCitys()
		},
		onLoad() {
			this.initHostCitys()
		},
		methods: {
			initHostCitys() {
				let list = Districts,
					arr = [],
					rd
				for(let i = 0; i < 26; ++i) {
					rd = Math.random() * 2000 ^ 0
					arr.push(list[rd])
				}
				if(arr.length % 3 == 2) {
					arr.pop()
				}
				this.citys = arr
				uni.stopPullDownRefresh()
			},
			searchItem() {
				let vm = this,
					tag = vm.searchText,
					searchList = Districts,
					reg = new RegExp(tag),
					arr
				if(!tag) {
					vm.list = []
					vm.menuListState = false
					return
				}
				vm.menuListState = true
				arr = searchList.filter(value => {
					if(reg.test(value.name)) {
						return value
					}
				})
				vm.list = arr
			},
			addToSideList(data) {
				uni.setStorage({
					key: "sideList",
					data,
					success() {
						uni.showToast({
							title: "保存成功",
							duration: 1200,
							success() {
								setTimeout(() => {
									uni.redirectTo({
										url: "./../sideList"
									})
								}, 1000)
								
							},
						})
					},
					fail() {
						uni.showToast({
							title: "添加失败",
							duration: 1000,
							icon: "none",
						})
					},
				})
			},
			addItem(value, index) {
				var vm = this,
					lock = true,
					len
				uni.getStorage({
					key: "sideList",
					success({data}) {
						len = data.length
						if(len > 0) {
							for(let i = 0; i < len; ++i) {
								if(data[i].id == value.id) {
									uni.showToast({
										duration: 1000,
										title: "不可重复添加",
										icon: "none",
									})
									lock = false
									break
								}
							}
							if(lock) {
								data.push(value)
								vm.addToSideList(data)
							}
						} else {
							data.push(value)
							vm.addToSideList(data)
						}
					},
					fail() {
						vm.addToSideList([value])
					},
				})
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
				padding: 20upx 10upx;
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
			width: 200upx;
			height: 50upx;
			margin: 12upx 0;
			@{bdra}: 25upx;
			display: flex;
			@{ai}: center;
			@{jc}: center;
			border: 2upx solid #ddd;
			@{fs}: 32upx;
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
