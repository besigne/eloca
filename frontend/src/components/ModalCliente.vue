<template>
    <div v-if="showModal" class="modal">
        <div class="modal-content">
            <form @submit.prevent="salvarCliente" class="stacked-form">
                <label for="empresa">Empresa:</label>
                <input type="text" v-model="clientes.empresa" />

                <label for="codigo">Código:</label>
                <input type="text" v-model="clientes.codigo" />

                <label for="razao_social">Razão Social:</label>
                <input type="text" v-model="clientes.razao_social" />

                <label for="tipo">Tipo:</label>
                <input type="text" v-model="clientes.tipo" />

                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" v-model="clientes.cpf_cnpj" />

                <button type="submit" @click.event="salvarInformacaoCliente">Salvar</button>
                <button @click="closeModal">Fechar</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        showModal: Boolean,
    },
    data() {
        return {
            clientes: {
                empresa: '',
                codigo: '',
                razao_social: '',
                tipo: '',
                cpf_cnpj: '',
            },
        };
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        salvarInformacaoCliente() {
            axios.post('http://127.0.0.1:8000/api/clientes/', this.clientes)
                .then(response => {
                    console.log(response.data)
                    this.salvarCliente();
                    this.closeModalCliente();
                })
                .catch(error => {
                    console.error('Erro ao salvar informação: ', error);
                })
        },
        salvarCliente() {
            this.$emit('cliente-saved', this.clientes);
            this.$emit('information-updated', this.clientes);
        },
    },

}
</script>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #464646;
    padding: 20px;
    border-radius: 8px;
}

.stacked-form {
    display: flex;
    flex-direction: column;
    max-width: 300px;
    /* Adjust the max-width as needed */
    margin: auto;
    /* Center the form */
}

.stacked-form label {
    margin-bottom: 5px;
}

.stacked-form input {
    margin-bottom: 10px;
    padding: 5px;
}

.stacked-form button {
    margin-bottom: 10px;
    padding: 5px;
}
</style>