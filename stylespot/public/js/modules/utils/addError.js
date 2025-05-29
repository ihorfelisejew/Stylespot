export function addError(block) {
    if (block.classList.contains("get-data__button")) {
        block.parentNode.previousElementSibling.classList.add("error");
    } else {
        block.previousElementSibling.classList.add("error");
    }
    block.classList.add("error");
}
