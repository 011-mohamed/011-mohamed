$('#add-image').click(function () {
    // je recupere le num de future champs que je vais creer
    // + c pour faire convertir en entier
    const index = + $('#widgets-counter').val();
    
    // je recupere le prototype des entrées(le code html qui gener un nouvelle entree )
    const tmpl = $('#annonce_images').data('prototype').replace(/_name_/g, index);
    
    // injecte ce code  dans la div qui s'appel annonce_image
    $('#annonce_images').append(tmpl);
    
    $('#widgets-counter').val(index + 1);
    // je gere le button supprimer
    handleDeleteButtons();
    });
    
    function handleDeleteButtons() { // je recupere tous les buttons qui ont  comme attribut de data action <<delete>>
    $('button[data-action="delete"]').click(function () {
    // this c le button que on click  et dataset c tous les attribut qui represente data-...
    // .target parceque ici je veut recuperer le data-target
    const target = this.dataset.target;
    $(target).remove();
    });
    
    }
    function updateCounter() {
    const count = + $('#annonce_images div.form-group').length;
    $('#widgets-counter').val(count);
    }
    updateCounter();
    handleDeleteButtons();