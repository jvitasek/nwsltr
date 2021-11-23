<template>
    <div class="composer__item" :id="component.id">
        <div class="center-wrapper">
            <div class="composer__tools">
                <button v-on:click="setBold(component.id)" title="Bold">
                    <svg class="icon icon--sm">
                        <title v-translate>Bold</title>
                        <use xlink:href="#bold"></use>
                    </svg>
                </button>
                <button v-on:click="setItalic(component.id)" title="Italic">
                    <svg class="icon icon--sm">
                        <title v-translate>Italic</title>
                        <use xlink:href="#italic"></use>
                    </svg>
                </button>
                <button v-on:click="setUnderline(component.id)" title="Underline">
                    <svg class="icon icon--sm">
                        <title v-translate>Underline</title>
                        <use xlink:href="#underline"></use>
                    </svg>
                </button>
                <div class="tool-wrapper-1">
                    <div class="tool-wrapper-2" :class="linkInputShowed ? 'active' : ''">
                        <input type="text" placeholder="URL" v-model="linkUrl">
                        <button v-on:click="setLink(component.id)">OK</button>                
                    </div>
                    <button v-on:click="showLinkInput()" title="Link">
                        <svg class="icon icon--sm">
                            <title v-translate>Link</title>
                            <use xlink:href="#link"></use>
                        </svg>
                    </button>
                </div>
                <button v-on:click="removeLink(component.id)" title="Unlink">
                    <svg class="icon icon--sm">
                        <title v-translate>Unlink</title>
                        <use xlink:href="#unlink"></use>
                    </svg>
                </button>
                <button v-on:click="handleRemove(component.id)" title="Remove component">
                    <svg class="icon">
                        <title v-translate>Remove</title>
                        <use xlink:href="#trash"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemTop(component)" title="Move up">
                    <svg class="icon">
                        <title v-translate>Move up</title>
                        <use xlink:href="#atop"></use>
                    </svg>
                </button>
                <button v-on:click="moveItemDown(component)" title="Move down">
                    <svg class="icon">
                        <title v-translate>Move down</title>
                        <use xlink:href="#adown"></use>
                    </svg>
                </button>
            </div>
            <div contenteditable="true"
                :class="component.paragraphType === 'perex' ? 'input paragraph paragraph-perex' : 'input paragraph editable'"
                :id="'input-'+component.id"
                @keydown="handleKeyDown"
                @paste="handlePaste"
                @input="handleInput"
                v-html="component.content"
            >
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            component: Object
        },
        data() {
            return {
                linkInputShowed: false,
                linkUrl: ''
            }
        },
        name: "Paragraph",
        methods: {
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
                    document.querySelector('#input-'+newItem.id).value = ''
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
                e.target.innerHTML += clipboardText[0]

                for(let i = 1; i <= clipboardText.length; i++) {
                    this.addParagraph('text', clipboardText[i])
                }
            },
            handleInput(e) {
                this.component.content = e.target.innerHTML
                this.$nextTick(() => { 
                    document.execCommand('selectAll', false, null);
                    document.getSelection().collapseToEnd();
                })
            },
            setBold() {
                document.execCommand('bold')
            },
            setItalic() {
                document.execCommand('italic')
            },
            setUnderline() {
                document.execCommand('underline')
            },
            setLink() {
                document.execCommand('CreateLink', false, this.linkUrl)
            },
            removeLink() {
                document.execCommand('unlink', false, false)
            },
            showLinkInput() {
                this.linkInputShowed = !this.linkInputShowed
            }
        },
        mounted() {
            document.querySelectorAll('.paragraph').forEach(function (item) {
                item.addEventListener('focus', () => {
                    item.innerHTML = item.innerHTML.replace('<p>', '').replace('</p>', ''); 
                });
            });
        }
    };
</script>