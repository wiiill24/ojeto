const btnSignIn = document.getElementById('btn-sign-in');
const btnSignUp = document.getElementById('btn-sign-up');
const forms = document.getElementById('forms');
const sidebar = document.getElementById('sidebar');
const signIn = document.getElementById('Sign-in'); 
const signUp = document.getElementById('sign-up');
const container = document.getElementById('container');
const linkSignIn = document.getElementById('link-sign-in');
const linkSignUp = document.getElementById('link-sign-up');

linkSignUp.addEventListener('click', () => {
    changeSignIn();
});

linkSignIn.addEventListener('click', () => {
    changeSignUp();
});

btnSignIn.addEventListener('click', () => {
    changeSignIn();
});

btnSignUp.addEventListener('click', () => {
    changeSignUp();
});

function changeSignIn() {
    forms.classList.remove('active');
    sidebar.classList.remove('active');
    container.style.animation = 'none';
    container.style.animation = 'bounce-up 1s ease';
    transition(signIn.children); 
}

function changeSignUp() {
    forms.classList.add('active');
    sidebar.classList.add('active');
    container.style.animation = 'none';
    container.style.animation = 'bounce-down 1s ease';
    transition(signUp.children);
}

function transition(children) {
    // Reinicia animacion
    Array.from(children).forEach((child) => {
        child.style.opacity = '0';
        child.style.animation = 'none';
    });
    
    // animacion
    setTimeout(() => {
        Array.from(children).forEach((child, index) => {
            child.style.animation = 'slideIn 0.4s ease forwards';
            let delay = (index * 0.05) + 's';
            child.style.animationDelay = delay;
        });
    }, 300);
}