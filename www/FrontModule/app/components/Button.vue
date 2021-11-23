<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="handleRemove(component.id)">
                    <svg class="icon">
                        <title v-translate>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)">
                    <svg class="icon">
                        <title v-translate>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)">
                    <svg class="icon">
                        <title v-translate>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
                <button v-on:click="toggleLinkForm(component)">
                    <svg class="icon">
                        <title v-translate>Set link</title>
                        <use xlink:href="#link"></use>
                    </svg>
                </button>
            </div>
            <div contenteditable="true"
                :class="['input button', placeholder ? 'placeholder' : '']"
                :id="'input-'+component.id" 
                :placeholder="this.$gettext('Insert button text')"
                @input="onInput"
            >{{ component.content }}</div>
            <div class="link-form" v-if="linkForm">
                <div class="heading" v-translate>Button URL</div>
                <div class="d-flex">
                    <input type="text" :placeholder="this.$gettext('Type url')" v-model="component.link" class="me-2">
                    <button type="button" v-on:click="setLink()" class="btn btn-primary btn-sm" v-translate>Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    
    export default {
        data() {
            return {
                linkForm: true,
                placeholder: true
            }
        },
        props: {
            component: Object
        },
        name: "Button",
        methods: {
            onInput(e) {
                this.component.content = e.target.innerText

                if(e.target.innerHTML.length > 0) {
                    this.placeholder = false
                }
                else {
                    this.placeholder = true
                }
            },
            handleRemove(id) {
                if(this.component.required) {
                    this.$toasted.error(this.$gettext('Cannot remove a required field'))
                }
                else {
                    this.$store.commit('removeComposerItem', id)
                }
            },
            moveItemTop(item) {
                if(this.component.required) {
                    this.$toasted.error(this.$gettext('Cannot move a required field'))
                }
                else {
                    this.$store.commit('moveItemTop', item, 1)
                }
            },
            moveItemDown(item) {
                if(this.component.required) {
                    this.$toasted.error(this.$gettext('Cannot move a required field'))
                }
                else {
                    this.$store.commit('moveItemDown', item, 1)
                }
            },
            toggleLinkForm() {
                this.linkForm = !this.linkForm
            },
            setLink() {
                this.linkForm = !this.linkForm
                let postData = this.component
                postData.mailingId = this.$store.getters.getComposer.id

                const config = {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                }

                const url = '/api/element/save'

                axios.post(url, postData, config)
                    .then((result) => {
                        console.log(result)
                    })
                    .catch((err) => {
                        console.log(err)
                        this.$toasted.error(this.$gettext('There was an error while saving the record'))
                    })
            }
        },
        mounted() {
            if(this.component.content.length > 0) {
                this.placeholder = false
            }
            document.querySelectorAll('.button').forEach(function (item, index) {
                item.addEventListener('keydown', (e) => {
                    if (e.which === 13) {
                        e.preventDefault();
                    }
                });
                item.addEventListener('focus', () => {
                    item.innerHTML = item.innerHTML.replace('<p>', '').replace('</p>', ''); 
                });
            });
        }
    };
</script>