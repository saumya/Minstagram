//
// version: 1.0.0
//

(message=>{
    console.log('Settings version', message);

    const btnInstallDB = document.getElementById('id_btn_installDB');

    btnInstallDB.addEventListener('click', event=>{
        event.preventDefault();
        console.log('Initialise Database');
        // TODO:
        
    });

})('1.0.0')