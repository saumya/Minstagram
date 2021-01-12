//
// version: 1.0.0
//

(message=>{
    console.log('Settings version', message);

    const btnInstallDB = document.getElementById('id_btn_installDB');

    const installDB = function(){
        console.log('installDB');
        //TODO: call server for the DB initialisation
    };
    // Event Handlers
    btnInstallDB.addEventListener('click', event=>{
        event.preventDefault();
        console.log('Initialise Database');
        installDB();
    });

})('1.0.0');