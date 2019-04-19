<template>
	<div class="home-index">
		<el-dialog :title="titleStatus ? '添加用户信息' : '修改用户信息'" :visible.sync="dialogFormVisible" :width="'40%'">
			<div class="input-item">
				<el-input placeholder="电话号码" v-model="phone" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;电话号码&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div class="input-item mt">
				<el-input placeholder="用户姓名" v-model="name" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;用户姓名&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div class="input-item mt">
				<el-input type="password" placeholder="用户密码" v-model="pwd" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;用户密码&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormVisible = false">取 消</el-button>
				<el-button type="primary" @click="recodeEvent">确 定</el-button>
			</div>
		</el-dialog>
		<el-dialog title="用户收藏地址" :visible.sync="dialogUserSideState">
			<el-table :data="userSideList" border>
				<el-table-column type="index" label="序号" width="50"></el-table-column>
				<el-table-column prop="code" label="城市编号" width="140"></el-table-column>
				<el-table-column prop="side" label="城市名称"></el-table-column>
			</el-table>
		</el-dialog>
		<header>
			<div>
				<el-button type="primary" icon="el-icon-plus" @click="addEvent"></el-button>
				&ensp;
				<el-input v-model="searchText" style="width: 300px" placeholder="请输入内容"></el-input>
				&ensp;&ensp;
				<el-button icon="el-icon-search" @click="searchEvent"></el-button>
			</div>
			<div><el-button type="success" icon="el-icon-refresh" @click="initData"></el-button></div>
		</header>
		<div class="home-index-bd">
			<el-table :data="info" border>
				<el-table-column type="index" label="序号" width="50"></el-table-column>
				<el-table-column prop="phone" label="电话" width="180"></el-table-column>
				<el-table-column prop="name" label="姓名" width="180"></el-table-column>
				<el-table-column prop="time" label="最后登录时间" width="200"></el-table-column>
				<el-table-column label="操作">
					<template slot-scope="scope">
						<el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
						<el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
						<el-button size="mini" type="info" 
						@click="handleShow(scope.$index, scope.row)">用户收藏地址</el-button>
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
			phone: '',
			name: '',
			pwd: '',
			id: '',
			searchText: '',
			titleStatus: false,
			loadingUserSide: "",
			userSideList: [],
			dialogUserSideState: false,
		};
	},
	created() {
		this.$store.commit('setProgressPage', 100);
		this.getUsers();
	},
	beforeDestory() {
		this.$store.commit('setProgressPage', 0);
	},
	methods: {
		handleEdit(index, row) {
			let vm = this;
			vm.phone = row.phone;
			vm.name = row.name;
			vm.id = row.id;
			vm.pwd = '';
			vm.titleStatus = false;
			vm.dialogFormVisible = true;
		},
		handleDelete(index, row) {
			let vm = this;
			vm.$confirm('此操作将永久删除用户, 是否继续?', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			})
				.then(() => {
					vm.$req(
						'post',
						{
							c: 'user',
							f: 'deleteRow',
							id: row.id
						},
						res => {
							if (res.state == 1) {
								vm.$message({
									type: 'success',
									message: '删除成功'
								});
								vm.searchEvent();
							} else {
								vm.$message({
									type: 'info',
									message: '删除失败'
								});
							}
						}
					);
				})
				.catch(() => {
					vm.$message({
						type: 'info',
						message: '已取消删除'
					});
				});
		},
		recodeEvent() {
			let vm = this,
				reg = /^1\d{10}$/;
			if (!reg.test(vm.phone)) {
				vm.$message({
					type: 'info',
					message: '用户手机号不合法',
					duration: 1200
				});
				return;
			}
			if (!vm.pwd) {
				vm.$message({
					type: 'info',
					message: '密码不可为空',
					duration: 1200
				});
				return;
			}
			if (!vm.titleStatus) {
				// edit
				vm.$req(
					'post',
					{
						c: 'user',
						f: 'recodeRow',
						name: vm.name,
						phone: vm.phone,
						pwd: vm.pwd,
						id: vm.id
					},
					res => {
						if (res.state == 1) {
							vm.$message({
								type: 'success',
								message: '修改用户信息，成功',
								duration: 1200
							});
							vm.searchEvent();
						} else {
							vm.$message({
								type: 'info',
								message: '修改失败，请检查参数',
								duration: 1200
							});
						}
						vm.dialogFormVisible = false;
					}
				);
			} else {
				// add
				vm.$req(
					'post',
					{
						c: 'user',
						f: 'addRow',
						name: vm.name,
						phone: vm.phone,
						pwd: vm.pwd
					},
					res => {
						if (res.state == 1) {
							vm.$message({
								type: 'success',
								message: '添加成功',
								duration: 1200
							});
							vm.searchEvent();
						} else {
							vm.$message({
								type: 'info',
								message: '添加失败，请检查参数',
								duration: 1200
							});
						}
						vm.dialogFormVisible = false;
					}
				);
			}
		},
		addEvent() {
			let vm = this;
			vm.phone = '';
			vm.name = '';
			vm.id = '';
			vm.pwd = '';
			vm.titleStatus = true;
			vm.dialogFormVisible = true;
		},
		getUsers() {
			let vm = this;
			vm.$req(
				'post',
				{
					page: vm.$store.getters.getPage,
					pageSize: vm.$store.getters.getPageSize,
					c: 'user',
					f: 'getRows',
					search: vm.searchText
				},
				res => {
					vm.info = res.data;
					vm.$store.commit('setRowsCount', res.totalCount);
				}
			);
		},
		initData() {
			this.searchText = '';
			this.searchEvent();
		},
		searchEvent() {
			let vm = this;
			if (vm.$store.getters.getPage == 1) {
				vm.getUsers();
			} else {
				vm.$store.commit('setPage', 1);
			}
		},
		handleShow(index, row) {
			let id = row.id,
				vm = this
			vm.$req("post", {
				c: 'user',
				f: "getSides",
				id,
			}, res => {
				if(res.data.sides.length == 0) {
					vm.$message({
						message: "用户[" + res.data.userName + "]暂无收藏地址~",
						type: "info",
						duration: 1200 
					})
					return
				}
				vm.userSideList = res.data.sides
				vm.dialogUserSideState = true				
			})
		}
	},
	watch: {
		'$store.getters.getPage'() {
			this.getUsers();
		}
	}
};
</script>
<style lang="less" scoped>
@import '../config';
.home-index {
	.el-table {
		margin-top: 20px;
		width: auto;
	}
}
.mt {
	margin-top: 20px;
}
header {
	display: flex;
	@{jc}: space-between;
}
</style>
