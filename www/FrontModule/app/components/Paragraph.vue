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
            <textarea ref="input"
                :class="component.paragraphType === 'perex' ? 'input paragraph editable paragraph-perex' : 'input paragraph editable'"
                :id="'input-'+component.id"
                @keydown="handleKeyDown"
                @paste="handlePaste"
                @input="handleInput"
                v-model="component.content"
            >
            </textarea>
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
            addParagraph(paragraphType, paragraphContent) {
                this.$store.commit('setComposerlastComponentId', parseInt(this.$store.getters.getComposerlastComponentId)+1)
                let newItem = {
                    id: this.$store.getters.getComposerlastComponentId,
                    component: 'Paragraph',
                    paragraphType: paragraphType,
                    content: paragraphContent,
                    required: false
                }
                this.$store.commit('pushComposerItem', newItem)
                this.$nextTick(() => { 
                    document.querySelector('#input-'+newItem.id).focus()
                    // document.querySelector('#input-'+newItem.id).value = ''
                });
            },
            handleKeyDown(e) {
                if(e.key === 'Enter') {
                    e.preventDefault();
                    this.addParagraph('text')
                }
            },
            handlePaste(e) {
                e.preventDefault()
                let clipboardText = e.clipboardData.getData('text').split(/\r?\n/)
                this.component.content = clipboardText[0]
                
                for(let i = 1; i <= clipboardText.length; i++) {
                    this.addParagraph('text', clipboardText[i])
                }
            },
            handleInput() {
                console.log(this.component.content)
            }
        },
        mounted() {
            // adjust height
            document.querySelectorAll('.editable').forEach(function (item) {
                item.addEventListener('input', () => {
                    item.style.height = "1px";
                    item.style.height = (item.scrollHeight) + "px";
                })
            })

            // const editor = new MediumEditor('.editable', {
            //     singleEnterBlockElement: false,
            //     toolbar: {
            //         buttons: ['bold', 'italic', 'underline', 'anchor'], 
            //     },
            //     placeholder: {
            //         text: 'Insert paragraph text',
            //     }
            // });
            
            // document.querySelectorAll('.editable').forEach(function (item, index) {
            //     item.addEventListener('focus', () => {
            //         item.innerHTML = item.innerHTML.replace('<p>', '').replace('</p>', ''); 
            //     });
            // });
        }
    };
</script>