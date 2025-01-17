using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Filters;
using System.Net;
using System.Security.Cryptography;
using System.Text;

namespace ASPNET_WebAPI.Authentication
{
    public class ApiKeyAuthFilter : IAuthorizationFilter
    {
        private readonly IConfiguration _configuration;

        public ApiKeyAuthFilter(IConfiguration configuration)
        {
            _configuration = configuration;
        }

        public void OnAuthorization(AuthorizationFilterContext context)
        {
            if (context.ActionDescriptor.EndpointMetadata.Any(em => em is IgnoreApiKeyAuthFilter))
            {
                return;
            }

            if (!context.HttpContext.Request.Headers.TryGetValue("x-api-key", out var key))
            {
                context.Result = new UnauthorizedObjectResult("API Key is missing");
                return;
            }

            var controller = context.RouteData.Values["controller"]!.ToString();
            Console.WriteLine($"Controller: {controller}");

            var expectedKey = GetKey(controller!);
            var hashedKey = SHA256Hash(key!);
            Console.WriteLine($"Expected Key {expectedKey}");
            Console.WriteLine($"Given Key: {hashedKey}");

            if (hashedKey != expectedKey)
            {
                context.Result = new UnauthorizedObjectResult("Invalid API Key");
                return;
            }
        }

        private string GetKey(string controller) => controller switch
        {
            "Test" => _configuration["Test-Key"] ?? "", // fakeapikeyfortest_==
            "Chat" => _configuration["Chatbot-Key"] ?? "", // fakeapikeyforchatbot_==
            "Payment" => _configuration["Payment-Key"] ?? "", // fakeapikeyforpayment_==
            _ => ""
        };

        private string SHA256Hash(string input)
        {
            using var sha256 = SHA256.Create();
            var bytes = Encoding.UTF8.GetBytes(input);
            var hash = sha256.ComputeHash(bytes);
            return Convert.ToBase64String(hash);
        }
    }
}
