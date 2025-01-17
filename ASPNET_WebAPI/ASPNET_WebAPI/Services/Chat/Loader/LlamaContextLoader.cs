using LLama;
using LLama.Common;

namespace ASPNET_WebAPI.Services.ChatServices.Loader
{
    /// <summary>
    /// Class <c>LlamaContextLoader</c> is used to load the Llama context.
    /// Purpose of this class is reduce the overhead of loading the context in every service / every request.
    /// </summary>
    public class LlamaContextLoader
    {
        private LLamaContext _context;
        public LLamaContext Context => _context;

        public LlamaContextLoader(IConfiguration configuration)
        {
            var @params = new ModelParams(configuration["ModelPath"]!)
            {
                ContextSize = 2048,
                GpuLayerCount = 20,
                BatchSize = 512,
            };

            using var weights = LLamaWeights.LoadFromFile(@params);

            _context = new LLamaContext(weights, @params);
        }

    }
}
