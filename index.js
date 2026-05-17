const cron = require("node-cron");
const envoyerNewsletter = require("./mailer");

// tous les jours à 9h sauf samedi (6) et dimanche (0)
  cron.schedule("30 10 * * 1 ", () => {
  console.log("Envoi de la newsletter...");
  envoyerNewsletter();
});
