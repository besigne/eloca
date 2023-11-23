<template>
    <div v-if="showModal" class="modal">
        <div class="modal-content">
            <form @submit.prevent="salvarEmpresa" class="stacked-form">
                <label for="empresa">Empresa:</label>
                <input type="text" v-model="empresas.empresa" />

                <label for="codigo">Código:</label>
                <input type="text" v-model="empresas.codigo" />

                <label for="razao_social">Razão Social:</label>
                <input type="text" v-model="empresas.razao_social" />

                <label for="sigla">Sigla:</label>
                <input type="text" v-model="empresas.sigla" />

                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" v-model="empresas.cpf_cnpj" />

                <button type="submit" @click.event="salvarInformacaoEmpresa">Salvar</button>
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
            empresas: {
                empresa: '',
                codigo: '',
                sigla: '',
                razao_social: '',
            }
        }
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        salvarInformacaoEmpresa() {
            axios.post('http://127.0.0.1:8000/api/empresas/', this.empresas)
                .then(response => {
                    console.log(response.data)
                    this.salvarEmpresa();
                    this.closeModalEmpresa();
                })
                .catch(error => {
                    console.log('Erro ao salvar informação: ', error)
                })
        },
        salvarEmpresa() {
            this.$emit('empresa-saved', this.empresas);
            this.$emit('information-updated', this.empresas)
        }
    }
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