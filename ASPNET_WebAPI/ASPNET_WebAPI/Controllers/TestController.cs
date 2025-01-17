using ASPNET_WebAPI.Authentication;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;

namespace ASPNET_WebAPI.Controllers
{
    [Route("api/[controller]")]
    [ServiceFilter(typeof(ApiKeyAuthFilter))]
    [ApiController]
    public class TestController : ControllerBase
    {
        [HttpGet("SecrectGet")]
        public IActionResult SecrectGet()
        {
            return Ok("Hello World");
        }

        [HttpGet("PublicGet")]
        [IgnoreApiKeyAuthFilter]
        public IActionResult PublicGet()
        {
            return Ok("Hello World");
        }
    }
}
