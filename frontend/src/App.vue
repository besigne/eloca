<template>
  <ModalCliente :show-modal="isModalClienteOpen" @close="closeModalCliente">
    <form @submit.prevent="salvarCliente" @cliente-saved="handleClienteSaved"
      @information-updated="handleInformationClienteUpdated">

    </form>
  </ModalCliente>

  <ModalEmpresa :show-modal="isModalEmpresaOpen" @close="closeModalEmpresa">
    <form @submit.prevent="salvarEmpresa" @empresa-saved="handleEmpresaSaved"
      @information-updated="handleInformationEmpresaUpdated">

    </form>
  </ModalEmpresa>

  <EditModalCliente  :show-modal="isModalEditClienteOpen"  @close="closeModalEditCliente" :cliente="selectedCliente" :clienteId="editClienteKey">
  
  </EditModalCliente>

  <EditModalEmpresa  :show-modal="isModalEditEmpresaOpen" @close="closeModalEditEmpresa" :empresa="selectedEmpresa" :empresaId="editEmpresaKey">
  
  </EditModalEmpresa>
  <div class="block">
    <div v-if="clientes">
      <table class="striped-table">
        <caption>
          Clientes
        </caption>
        <tr>
          <th scope="col">Empresa</th>
          <th scope="col">Código</th>
          <th scope="col">Razão Social</th>
          <th scope="col">Tipo</th>
          <th scope="col">CPF/CNPJ</th>
        </tr>
        <tr v-for="cliente in clientes" :key="cliente.id">
          <td v-for="(value, key) in Object.entries(cliente).slice(1, -3)" :key="key" class="clickable-cell" @click="openModalEditCliente(cliente, cliente.id)">
            {{ value[1] }}
          </td>
          <button @click="deleteCliente(cliente.id)">Deletar</button>
        </tr>
      </table>
      <button class="button" @click="openModalCliente"> Novo Cliente</button>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
  <div class="block">
    <div v-if="empresas">
      <table class="striped-table">
        <caption>
          Empresas
        </caption>
        <tr>
          <th scope="col">Empresa</th>
          <th scope="col">Código</th>
          <th scope="col">Sigla</th>
          <th scope="col">Razão Social</th>
        </tr>
        <tr v-for="empresa in empresas" :key="empresa.id">
          <td v-for="(value, key) in Object.entries(empresa).slice(1, -3)" :key="key" class="clickable-cell" @click="openModalEditEmpresa(empresa, empresa.id)">
            {{ value[1] }}
          </td>
          <td>
            <button @click="deleteEmpresa(empresa.id)">Deletar</button>
          </td>
        </tr>
      </table>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
    <button class="button" @click="openModalEmpresa"> Nova Empresa</button>
  </div>
</template>

<script>
import axios from 'axios';
import ModalCliente from './components/ModalCliente.vue';
import ModalEmpresa from './components/ModalEmpresa.vue';
import EditModalCliente from './components/EditModalCliente.vue';
import EditModalEmpresa from './components/EditModalEmpresa.vue';

export default {
  components: {
    ModalCliente,
    ModalEmpresa,
    EditModalCliente,
    EditModalCliente,
    EditModalEmpresa
},
  data() {
    return {
      clientes: [],
      empresas: [],
      selectedCliente: [],
      selectedEmpresa: [],
      editClienteKey: '',
      editEmpresaKey: '',
      isModalClienteOpen: false,
      isModalEmpresaOpen: false,
      isModalEditClienteOpen: false,
      isModalEditEmpresaOpen: false,
    };
  },
  mounted() {
    Promise.all([
      axios.get('http://127.0.0.1:8000/api/clientes/'),
      axios.get('http://127.0.0.1:8000/api/empresas/'),
    ])
      .then(([clientesResponse, empresasResponse]) => {
        this.clientes = clientesResponse.data;
        this.empresas = empresasResponse.data;
      })
      .catch(error => {
        console.error('Error fetching data', error);
      });
  },
  methods: {
    openModalCliente() {
     
      this.isModalClienteOpen = true;
    },
    openModalEditCliente(cliente, clienteId) {
      this.selectedCliente = {...cliente};
      this.editClienteKey = clienteId;
      this.isModalEditClienteOpen = true;
    },
    closeModalCliente() {
      this.isModalClienteOpen = false;
    },
    closeModalEditCliente() {
      this.isModalEditClienteOpen = false;
    },
    handleClienteSaved(data) {
      console.log('Cliente saved: ', data);
      this.closeModalCliente();
    },
    handleInformationClienteUpdated(data) {

      this.clientes.push(data);
    },
    openModalEmpresa() {
      this.isModalEmpresaOpen = true;
    },
    openModalEditEmpresa(empresa, empresaId) {
      this.selectedEmpresa = {...empresa}
      this.editEmpresaKey = empresaId;
      this.isModalEditEmpresaOpen = true;
    },
    closeModalEmpresa() {
      this.isModalEmpresaOpen = false;
    },
    closeModalEditEmpresa()  {
      this.isModalEditEmpresaOpen = false;
    },
    handleEmpresaSaved(data) {
      console.log('Empresa saved: ', data);
      this.closeModalEmpresa();
    },
    handleInformationEmpresaUpdated(data) {
      this.empresas.push(data);
    },
    toggleEditClienteMode(cliente) {
      cliente.editMode = !cliente.editMode;
    },
    toggleEditEmpresaMode(empresa) {
      empresa.editMode   = !cliente.editMode;
    },
    deleteCliente(clienteId) {
      axios.delete(`http://127.0.0.1:8000/api/clientes/${clienteId}`)
        .then(response => {
          console.log('Client deleted: ', response.data);
        })
        .catch(error => {
          console.log('Error deleting Client: ', error);
        });
    },
    deleteCliente(empresaId) {
      axios.delete(`http://127.0.0.1:8000/api/clientes/${empresaId}`)
        .then(response => {
          console.log('Client deleted: ', response.data);
        })
        .catch(error => {
          console.log('Error deleting Client: ', error);
        });
    }

  }
};
</script>

<style>
.block {
  margin: 15px;
  padding: 2px;
}

.button {
  color: rgb(177, 174, 174);
  font-weight: bold;
  background-color: rgb(53, 51, 51);
  border: 'none';
  box-shadow: 'none';
  margin-left: 25px;
}

.clickable-cell {
  cursor: pointer;
}

.striped-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #333333;
  margin: 25px;
  padding: 15px;
}

.striped-table td {
  text-align: right;
  margin-right: 15px;
  padding-left: 15px;
  padding-right: 5px;
  border-top: 1px solid #333333;
}

.striped-table tr {
  text-align: right;
}
</style>