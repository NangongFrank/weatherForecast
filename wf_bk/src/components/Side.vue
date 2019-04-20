<template>
	<div class="home-index">
		<el-dialog :title="titleStatus ? '添加地区信息' : '修改地区信息'" :visible.sync="dialogFormVisible" :width="'40%'">
			<div class="input-item">
				<el-input placeholder="地区编号，如101250101" v-model="code" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;地区编号&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input placeholder="一级地区名称，如湖南" v-model="province" :clearable="true">
					<template slot="prepend">
						一级地区名称
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input placeholder="二级地区名称，如长沙" v-model="city" :clearable="true">
					<template slot="prepend">
						二级地区名称
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input placeholder="三级地区名称，如长沙或者宁乡" v-model="area" :clearable="true">
					<template slot="prepend">
						三级地区名称
					</template>
				</el-input>
			</div>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormVisible = false">取 消</el-button>
				<el-button type="primary" @click="recodeEvent">确 定</el-button>
			</div>
		</el-dialog>
		<header>
			<div>
				<el-button type="primary" icon="el-icon-plus" @click="addEvent"></el-button>
				&ensp;
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
				<el-table-column label="操作">
					<template slot-scope="scope">
						<el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
						<el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
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
			dialogFormVisible: false,
			code: '',
			area: '',
			city: "",
			province: "",
			searchText: '',
			titleStatus: false
		};
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
			vm.code = row.code
			vm.area = row.area
			vm.city = row.city
			vm.province = row.province
			vm.titleStatus = false
			vm.dialogFormVisible = true
		},
		handleDelete(index, row) {
			let vm = this
			vm.$confirm('此操作将永久删除地址, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					vm.$req(
						'post',
						{
							c: 'side',
							f: 'deleteRow',
							code: row.code
						},
						res => {
							if (res.state == 1) {
								vm.$message({
									type: 'success',
									message: '删除成功'
								})
								vm.searchEvent()
							} else {
								vm.$message({
									type: 'info',
									message: '删除失败'
								})
							}
						}
					)
				})
				.catch(() => {
					vm.$message({
						type: 'info',
						message: '已取消删除'
					})
				})
		},
		recodeEvent() {
			let vm = this
			if (!vm.titleStatus) {
				// edit
				vm.$req(
					'post',
					{
						c: 'side',
						f: 'recodeRow',
						area: vm.area,
						city: vm.city,
						province: vm.province,
						code: vm.code
					},
					res => {
						if (res.state == 1) {
							vm.$message({
								type: 'success',
								message: '修改地区信息，成功',
								duration: 1200
							})
							vm.searchEvent()
						} else {
							vm.$message({
								type: 'info',
								message: '修改失败，请检查参数',
								duration: 1200
							})
						}
						vm.dialogFormVisible = false
					}
				)
			} else {
				// add
				vm.$req(
					'post',
					{
						c: 'side',
						f: 'addRow',
						area: vm.area,
						city: vm.city,
						province: vm.province,
						code: vm.code
					},
					res => {
						if (res.state == 1) {
							vm.$message({
								type: 'success',
								message: '添加成功',
								duration: 1200
							})
							vm.searchEvent()
						} else {
							vm.$message({
								type: 'info',
								message: '添加失败，请检查参数',
								duration: 1200
							})
						}
						vm.dialogFormVisible = false
					}
				)
			}
		},
		addEvent() {
			let vm = this
			vm.code = ''
			vm.area = ''
			vm.city = ''
			vm.province = ''
			vm.titleStatus = true
			vm.dialogFormVisible = true
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
					search: vm.searchText
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
