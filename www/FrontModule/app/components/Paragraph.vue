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
            </div>
            <div contenteditable="true"
                ref="input"
                :class="component.paragraphType === 'perex' ? 'input paragraph editable paragraph-perex' : 'input paragraph editable'"
                :id="'input-'+component.id"
                @input="onInput"
                @keydown.enter="addParagraph('text')"
            >
                {{ component.content }}
            </div>
        </div>
    </div>
</template>

<script>
    import MediumEditor from 'medium-editor'

    export default {
        props: {
            component: Object
        },
        name: "Paragraph",
        methods: {
            onInput(e) {
                this.component.content = e.target.innerText
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
            addParagraph(paragraphType) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Paragraph',
                    paragraphType: paragraphType,
                    content: '',
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                setTimeout(function(){ 
                    let input = document.querySelector('#input-'+newItem.id)
                    input.focus()
                    input.innerHTML = ''
                }, 50); 
            }
        },
        mounted() {
            const editor = new MediumEditor('.editable', {
                singleEnterBlockElement: false,
                toolbar: {
                    buttons: ['bold', 'italic', 'underline', 'anchor'], 
                },
                placeholder: {
                    text: 'Insert paragraph text',
                }
            });
            
            document.querySelectorAll('.editable').forEach(function (item, index) {
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