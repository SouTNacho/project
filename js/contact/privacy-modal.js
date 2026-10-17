const privacyDialog = document.querySelector("#contact_privacy_dialog")
const openPrivacyDialog = document.querySelector("#open_privacy_dialog")
const closePrivacyDialog = document.querySelector("#close_privacy_dialog")

if (privacyDialog && openPrivacyDialog && closePrivacyDialog) {
    openPrivacyDialog.addEventListener("click", (event) => {
        event.preventDefault()
        privacyDialog.showModal()
    })

    closePrivacyDialog.addEventListener("click", () => privacyDialog.close())
    privacyDialog.addEventListener("click", (event) => {
        if (event.target === privacyDialog) privacyDialog.close()
    })
}
