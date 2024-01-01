// utils/errorHandler.js

// Generic error handling function
export function handleErrorResponse(error) {
  console.error('Error:', error);

  // Default error message
  let message = 'An unexpected error occurred.';

  // Check if the error has a response with a data property
  if (error.response && error.response.data) {
    // If the error response includes error messages, format them
    if (error.response.data.errors) {
      const errors = error.response.data.errors;
      const errorMessages = [];
      for (let key in errors) {
        if (errors.hasOwnProperty(key)) {
          // Assume errors[key] is an array of messages
          errorMessages.push(`${key}: ${errors[key].join(', ')}`);
        }
      }
      // Join all error messages into a single string
      message = errorMessages.join('\n');
    } else if (error.response.data.message) {
      // If there's a single error message, use it
      message = error.response.data.message;
    }
  } else if (error.message) {
    // If the error object has a message property, use it
    message = error.message;
  }

  // Display the error message to the user
  alert(message);

  // Return the error message in case it needs to be displayed differently
  return message;
}
