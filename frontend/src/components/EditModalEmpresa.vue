<template>
    <div v-if="showModal" class="modal">
        <div class="modal-content">
            <form @submit.prevent="saveChanges" class="stacked-form">
                <label for="empresa">Empresa:</label>
                <input type="text" v-model="editedEmpresa.empresa" />

                <label for="codigo">Código:</label>
                <input type="text" v-model="editedEmpresa.codigo" />

                <label for="tipo">Sigla:</label>
                <input type="text" v-model="editedEmpresa.sigla" />

                <label for="razao_social">Razão Social:</label>
                <input type="text" v-model="editedEmpresa.razao_social" />

                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" v-model="editedEmpresa.cpf_cnpj" />

                <button type="submit" @click.event="saveChanges">Salvar</button>
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
        empresa: Object,
        empresaId: Number
    },
    data() {
        return {
            editedEmpresa: { ...this.empresa },
            empresaId: this.empresaId
        };
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        saveChanges() {
            axios.put(`http://127.0.0.1:8000/api/empresas/${this.empresaId}`, this.editedEmpresa)
                .then(response => {
                    console.log('Cliente updated:', response.data);
                    this.$emit('save', this.editedEmpresa);
                    this.closeModal();
                })
                .catch(error => {
                    console.error('Error updating cliente:', error);
                });
        },
    },
};
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