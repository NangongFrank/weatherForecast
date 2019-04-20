<template>
	<div class="home-index">
		<header>
			<div>
				<el-input v-model="searchText" style="width: 300px" placeholder="城市编号/城市[一 | 二 | 三]级名称"></el-input>
				&ensp;&ensp;
				<el-button icon="el-icon-search" @click="searchEvent"></el-button>
			</div>
			<div><el-button type="success" icon="el-icon-refresh" @click="initData"></el-button></div>
		</header>
		<div class="home-index-bd">
			<el-table :data="info" border>
				<el-table-column type="index" label="序号" width="50"></el-table-column>
				<el-table-column prop="code" label="地区编号" width="180"></el-table-column>
				<el-table-column prop="area" label="三级地区名" width="180"></el-table-column>
				<el-table-column prop="city" label="二级地区名" width="180"></el-table-column>
				<el-table-column prop="province" label="一级地区名" width="180"></el-table-column>
				<el-table-column prop="order" label="排序" width="80"></el-table-column>
				<el-table-column label="操作">
					<template slot-scope="scope">
						<el-button size="mini" @click="handleEdit(scope.$index, scope.row)">更新排序</el-button>
						<el-button size="mini" type="danger"
						@click="handleCacnel(scope.$index, scope.row)">取消热门城市</el-button>
					</template>
				</el-table-column>
			</el-table>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			info: [],
			searchText: '',
		}
	},
	created() {
		this.$store.commit('setProgressPage', 100)
		this.getSides()
	},
	beforeDestory() {
		this.$store.commit('setProgressPage', 0)
	},
	methods: {
		handleEdit(index, row) {
			let vm = this
			vm.$prompt("新的序号", '提示', {
				inputPattern: /^\d{1,10}$/,
				inputErrorMessage: "必须输入[1-10]位数字",
			}).then(({value}) => {
				vm.$req("post", {
					f: 'changeOrder',
					c: 'side',
					id: row.id,
					order: value,
				}, res => {
					if(res.state == 1) {
						vm.$notify({
							title: "成功",
							message: "排序更新成功",
							type: "success"
						})
						vm.getSides()
					} else {
						vm.$notify({
							title: "失败",
							message: "排序更新失败",
							type: "warning"
						})
					}
				})
			}).catch(() => {
				vm.$message({
					type: "info",
					message: "已取消更改排序",
					duration: 1500
				})
			})
		},
		handleCacnel(index, row) {
			let vm = this,
				message
			message = "确定取消 [" + (row.province + '-' + row.city + '-' + row.area) + "] 热门城市?"
			vm.$confirm(message, '提示', {
				type: "warning"
			}).then(() => {
				message = "rmHotCity"
				vm.$req(
					'post',
					{
						c: 'side',
						f: message,
						code: row.code,
						id: row.id,
					},
					res => {
						if (res.state == 1) {
							vm.$message({
								type: 'success',
								message: '操作成功!',
								duration: 1200
							})
							vm.searchEvent()
						} else {
							vm.$message({
								type: 'warning',
								message: '操作有误...',
								duration: 1200
							})
						}
					}
				)
			}).catch(() => {
				vm.$message({
					type: "info",
					message: "已取消操作..",
					duration: 1500,
				})
			})
		},
		getSides() {
			let vm = this
			vm.$req(
				'post',
				{
					f: 'getRows',
					c: 'side',
					page: vm.$store.getters.getPage,
					pageSize: vm.$store.getters.getPageSize,
					hot: true,
				},
				res => {
					vm.info = res.data
					vm.$store.commit('setRowsCount', res.totalCount)
				}
			)
		},
		initData() {
			this.searchText = ''
			this.searchEvent()
		},
		searchEvent() {
			let vm = this,
				nowpage = vm.$store.getters.getPage
			if (nowpage == 1) {
				vm.getSides()
			} else {
				vm.$store.commit('setPage', 1)
			}
		}
	},
	watch: {
		'$store.getters.getPage'() {
			this.getSides()
		}
	}
}
</script>
<style lang="less" scoped>
@import '../config';
.home-index {
	.el-table {
		margin-top: 20px;
		width: auto;
	}
}
.el-dialog {
	.input-item {
		margin: 10px 0;
	}
}
header {
	display: flex;
	@{jc}: space-between;
}
</style>
