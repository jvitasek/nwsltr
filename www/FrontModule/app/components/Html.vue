<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="handleRemove(component.id)">
                    <svg class="icon">
                        <title>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)">
                    <svg class="icon">
                        <title>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)">
                    <svg class="icon">
                        <title>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
                <button v-on:click="toggleForm(component)">
                    <svg class="icon">
                        <title>Set html</title>
                        <use xlink:href="#edit"></use>
                    </svg>
                </button>
            </div>
            <div class="embed-html" v-html="component.content"></div>
            <div class="link-form" v-if="form">
                <div class="heading">HTML</div>
                <div class="d-flex">
                    <textarea placeholder="Type HTML" v-model="component.content" class="me-2"></textarea>
                    <button type="button" v-on:click="toggleForm()" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: true,
                placeholder: true
            }
        },
        props: {
            component: Object
        },
        name: "Html",
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
                    this.$toasted.error('Cannot remove a required field')
                }
                else {
                    this.$store.commit('removeComposerItem', id)
                }
            },
            moveItemTop(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemTop', item, 1)
                }
            },
            moveItemDown(item) {
                if(this.component.required) {
                    this.$toasted.error('Cannot move a required field')
                }
                else {
                    this.$store.commit('moveItemDown', item, 1)
                }
            },
            toggleForm() {
                this.form = !this.form
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